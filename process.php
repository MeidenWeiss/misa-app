<?php
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

include 'config.php';
include 'class.requests.php';
include 'class.admin.php';
include 'class.appointments.php';
include 'class.logs.php';
include 'class.posts.php';
include 'class.schedules.php';

$requests = new Requests();
$admin = new Admin();
$sched = new Schedules();
$appt = new Appointments();
$logs = new Logs();
$posts = new Posts();

function timeCheck($startTime, $start_compare, $endTime, $end_compare){
    $from = strtotime($startTime);
    $from_compare = strtotime($start_compare);
    $to = strtotime($endTime);
    $to_compare = strtotime($end_compare);
    $intersect = min($to, $to_compare) - max($from, $from_compare);

    if($intersect < 0)
        $intersect = 0;
        $overlap = $intersect / 3600;
    if($overlap <= 0){
        // There are no time conflicts
        return true;
    }else{
        // There is a time conflict
        return false;
    }
}

function schedConflict($startDate, $startTime, $endTime){ // if True = conflict
    $db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    $sc = array();
    $ec = array();

    $sql ="SELECT startTime FROM tbl_schedules WHERE startDate = '$startDate' AND sched_status = 'SCHEDULED'";
	$query = mysqli_query($db,$sql);
	while($row = mysqli_fetch_assoc($query)){
        $result = $row['startTime'];
		array_push($sc, $result);
	}

    $sql2 ="SELECT endTime FROM tbl_schedules WHERE startDate = '$startDate' AND sched_status = 'SCHEDULED'";
	$query2 = mysqli_query($db,$sql2);
	while($row2 = mysqli_fetch_assoc($query2)){
        $result2 = $row2['endTime'];
		array_push($ec, $result2);
	}

    $start_compare = $sc;
    $end_compare = $ec;

    $true = 0;
    $message = "";
    $countStart = count($start_compare);
    for($i = 0; $i < $countStart; $i++){
        $result = timeCheck($startTime, $start_compare[$i], $endTime, $end_compare[$i]);
        if($result != true){
            $true++;
            $st = $start_compare[$i];
            $et = $end_compare[$i];
            $sd = date_create($startDate);
            $s_time = date_create($st);
            $e_time = date_create($et);
            $date = date_format($sd, "F j - (l)");
            $stime = date_format($s_time, "h:i A");
            $etime = date_format($e_time, "h:i A");
            $fail = $date . " " . "Conflict in " . " " . $stime . " - " . $etime;
        }else{
            // No Conflict...
        }
    }
    if($true >= 1){
        return $fail;
    }else{
        return $success = "No Conflict";
    }
}

function apptConflict($startDate, $startTime, $endTime){ // if True = conflict
    $db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    $sc = array();
    $ec = array();

    $sql ="SELECT startTime FROM tbl_appointments WHERE startDate = '$startDate' AND appt_status = 'RECURRING'";
	$query = mysqli_query($db,$sql);
	while($row = mysqli_fetch_assoc($query)){
        $result = $row['startTime'];
		array_push($sc, $result);
	}

    $sql2 ="SELECT endTime FROM tbl_appointments WHERE startDate = '$startDate' AND appt_status = 'RECURRING'";
	$query2 = mysqli_query($db,$sql2);
	while($row2 = mysqli_fetch_assoc($query2)){
        $result2 = $row2['endTime'];
		array_push($ec, $result2);
	}

    $start_compare = $sc;
    $end_compare = $ec;

    $true = 0;
    $message = "";
    $countStart = count($start_compare);
    for($i = 0; $i < $countStart; $i++){
        $result = timeCheck($startTime, $start_compare[$i], $endTime, $end_compare[$i]);
        if($result != true){
            $true++;
            $st = $start_compare[$i];
            $et = $end_compare[$i];
            $sd = date_create($startDate);
            $s_time = date_create($st);
            $e_time = date_create($et);
            $date = date_format($sd, "F j - (l)");
            $stime = date_format($s_time, "h:i A");
            $etime = date_format($e_time, "h:i A");
            $fail = $date . " " . "Conflict in " . " " . $stime . " - " . $etime;
        }else{
            // No Conflict...
        }
    }
    if($true >= 1){
        return $fail;
    }else{
        return $success = "No Conflict";
    }
}

switch($action){
    case 'req-process': // requests.php Approve & Cancel Requests
        $set = $_GET['set'];
        switch($set){
            case '1': // from requests.php
                $submit = $_POST['submit'];
                if($submit == "Approve"){
                    $id = $_POST['req_id'];
                    $view_request = $requests->get_request($id);

                    foreach($view_request as $item){
                        $type = $item['req_type'];
                        $priest = $item['req_priest'];
                        $client = $item['req_client'];
                        $contact = $item['contact_no'];
                        $email = $item['client_email'];
                        $startDate = $item['startDate'];
                        $startTime = $item['startTime'];
                        $endDate = $item['endDate'];
                        $endTime = $item['endTime'];
                        $note = $item['req_note'];

                        // check for conflicts
                        $apptResult = apptConflict($startDate, $startTime, $endTime);
                        $schedResult = schedConflict($startDate, $startTime, $endTime);
                        if($apptResult == "No Conflict" && $schedResult == "No Conflict"){
                            // $requests->approve_req($id); // Modifty Req - mark APPROVED
                            $requests->approve_req($id);
                            // log changes
                            $log_type = "REQUESTS";
                            $admin = $_SESSION['admin_name'];
                            $log_desc = "Request have been approved.";
                            $logs->new_log($log_type, $admin, $log_desc);
                            // send to tbl_appt
                            $appt->new_appt($type, $priest, $client, $contact, $email, $startDate, $startTime, $endDate, $endTime, $note);
                            // log changes
                            $log_type2 = "APPOINTMENTS";
                            $log_desc2 = "Appointment have been scheduled.";
                            $logs->new_log($log_type2, $admin, $log_desc2);
                            header("location: dashboard.php?page=requests");
                        }else{
                            $message = "There was a conflict in the scheduled date please reschedule.";
                            header("location: dashboard.php?page=requests&message=$message");
                        }
                    }
                }else{ // --- CANCEL REQUEST ---
                    $req_id = $_POST['req_id'];
                    $requests->cancel_req($req_id);
                    
                    // tbl_log, log changes
                    $log_type = "REQUESTS";
                    $admin = $_SESSION['admin_name'];
                    $log_desc = "Request have been canceled.";
                    $logs->new_log($log_type, $admin, $log_desc);
                    header("location: dashboard.php?page=requests");
                }
            break;
            case '2': // from adj_requests.php
                $id = $_POST['req_id'];
                $type = $_POST['req_type'];
                $startDate = $_POST['startDate'];
                $endDate = $_POST['startDate'];
                $startTime = $_POST['startTime'];
                $endTime = $_POST['endTime'];
                $priest = $_POST['req_priest'];
                $client = $_POST['client'];
                $contact = $_POST['contact'];
                $email = $_POST['email'];
                $note = $_POST['note'];

                // check for conflicts
                $apptResult = apptConflict($startDate, $startTime, $endTime);
                $schedResult = schedConflict($startDate, $startTime, $endTime);
                if($apptResult == "No Conflict" && $schedResult == "No Conflict"){
                    //adjust Request
                    $requests->update_req($id, $type, $priest, $client, $contact, $email, $startDate, $startTime, $endDate, $endTime, $note);
                    // $requests->approve_req($id); // Modifty Req - mark APPROVED
                    $requests->approve_req($id);
                    // log changes
                    $log_type = "REQUESTS";
                    $admin = $_SESSION['admin_name'];
                    $log_desc = "Request have been approved.";
                    $logs->new_log($log_type, $admin, $log_desc);
                    // send to tbl_appt
                    $appt->new_appt($type, $priest, $client, $contact, $email, $startDate, $startTime, $endDate, $endTime, $note);
                    // log changes
                    $log_type2 = "APPOINTMENTS";
                    $log_desc2 = "Appointment have been scheduled.";
                    $logs->new_log($log_type2, $admin, $log_desc2);
                    header("location: dashboard.php?page=requests");
                }else{
                    $message = "There was a conflict in the scheduled date please reschedule.";
                    header("location: dashboard.php?page=adj-request&id=$id&message=$message");
                }
            break;
        }
    break;
    case 'process-appt': // Red Process button
        $appt_id = $_POST['appt_id'];
        $pay_status = "NOT PAID";
        // tbl_appointments UPDATE, mark "DONE" & update payment
        $appt_status = "DONE";
        $view_appt = $appt->cancel_appt($appt_id, $appt_status, $pay_status);

        // tbl_log, log changes
        $log_type = "APPOINTMENTS";
        $admin = $_SESSION['admin_name'];
        $log_desc = "Appointment have been concluded.";
        $logs->new_log($log_type, $admin, $log_desc);

        header("location: dashboard.php?page=appointments");
    break;
    case 'new-schedule':
        $appt_id = $_POST['appt_id'];
        $pay_status = $_POST['pay_status']; // Update tbl_appt

        $title = $_POST['sched_title'];
        $type = $_POST['sched_type'];
        $priest = $_POST['sched_priest'];
        $category = $_POST['category']; // Public or Public
        $client = $_POST['client_name'];
        $contact = $_POST['contact_no'];
        $email = $_POST['client_email'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $note = $_POST['sched_note'];

        // check conflict
        $apptResult = apptConflict($startDate, $startTime, $endTime);
        $schedResult = schedConflict($startDate, $startTime, $endTime);

        if($apptResult == "No Conflict" && $schedResult == "No Conflict"){
            $appt->update_appt($appt_id); // Mark Appointment DONE
            $appt->update_pay($appt_id, $pay_status); //Mark Appointment Payment Status

            // tbl_log, log changes
            $log_type = "APPOINTMENTS";
            $admin = $_SESSION['admin_name'];
            $log_desc = "Appointment have been concluded.";
            $logs->new_log($log_type, $admin, $log_desc);

            // send to tbl_schedules
            $sched->new_sched($title, $category, $type, $priest, $client, $contact, $email, $startDate, $startTime, $endDate, $endTime, $note);
            // log changes
            $log_type2 = "SCHEDULES";
            $log_desc2 = "Schedule have been created.";
            $logs->new_log($log_type2, $admin, $log_desc2);
            header("location: dashboard.php?page=appointments");
        }else{
            $message = "There was a conflict in the scheduled date please reschedule.";
            header("location: dashboard.php?page=view_appt&id=$appt_id&message=$message");
        }
    break;
    case 'reschedule': // reschedule appointment
        $appt_id = $_POST['appt_id'];
        $startDate = $_POST['startDate'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $priest = $_POST['appt_priest'];
        $note = $_POST['appt_note'];
        
        // check conflict
        $apptResult = apptConflict($startDate, $startTime, $endTime);
        $schedResult = schedConflict($startDate, $startTime, $endTime);
        if($apptResult == "No Conflict" && $schedResult == "No Conflict"){
            $appt->reschedule($appt_id, $startDate, $startTime, $endTime, $priest, $note);
            // tbl_log, log changes
            $log_type = "APPOINTMENTS";
            $admin = $_SESSION['admin_name'];
            $log_desc = "Appointment have been rescheduled.";
            $logs->new_log($log_type, $admin, $log_desc);
            header("location: dashboard.php?page=appointments");
        }else{
            $message = "There was a conflict in rescheduling the date. Please set a different date.";
            header("location: dashboard.php?page=view_appt&id=$appt_id&message=$message");
        }
    break;
    case 'create-sched':
        $title = $_POST['sched_title'];
        $type = $_POST['event_type'];
        $category = $_POST['category']; // Public or Public
        $priest = $_POST['sched_priest'];
        $client = $_POST['client_name'];
        $contact = $_POST['client_no'];
        $email = $_POST['client_email'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $note = $_POST['sched_note'];

        if($type == null || $priest == null || $startDate == null || $endDate == null || $startTime == null || $endTime == null){
            header("location: dashboard.php?page=new-sched");
        }else{
            // check conflict
            $apptResult = apptConflict($startDate, $startTime, $endTime);
            $schedResult = schedConflict($startDate, $startTime, $endTime);

            if($apptResult == "No Conflict" && $schedResult == "No Conflict"){
                // send to tbl_schedules
                $sched->new_sched($title, $category, $type, $priest, $client, $contact, $email, $startDate, $startTime, $endDate, $endTime, $note);
                // log changes
                $log_type2 = "SCHEDULES";
                $admin = $_SESSION['admin_name'];
                $log_desc2 = "Schedule have been created.";
                $logs->new_log($log_type2, $admin, $log_desc2);
                header("location: dashboard.php?page=schedules");
            }else{
                $message = "There was a conflict in the scheduled date please reschedule.";
                header("location: dashboard.php?page=new-sched&message=$message");
            }
        }
    break;
    case 'adj-sched': // adjust schedule
        $submit = $_POST['adjust'];
        $sched_id = $_POST['sched_id'];
        $priest = $_POST['sched_priest'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $note = $_POST['sched_note'];

        if($submit == "Adjust"){
            // check conflict
            $apptResult = apptConflict($startDate, $startTime, $endTime);
            $schedResult = schedConflict($startDate, $startTime, $endTime);

            if($apptResult == "No Conflict" && $schedResult == "No Conflict"){
                // adjust schedule
                $sched->update_sched($sched_id, $startDate, $endDate, $startTime, $endTime, $priest, $note);

                // log changes
                $log_type2 = "SCHEDULES";
                $admin = $_SESSION['admin_name'];
                $log_desc2 = "Schedule have been adjusted.";
                $logs->new_log($log_type2, $admin, $log_desc2);
                header("location: dashboard.php?page=schedules");
            }else{
                $message = "There was a conflict in rescheduling the date. Please set a different date.";
                header("location: dashboard.php?page=adj-sched&id=$sched_id&message=$message");
            }
        }else if($submit == "Done"){
            // postpone schedule
            $status = "DONE";
            $sched->mark_sched($sched_id, $status);

            // log changes
            $log_type2 = "SCHEDULES";
            $admin = $_SESSION['admin_name'];
            $log_desc2 = "Schedule have been concluded.";
            $logs->new_log($log_type2, $admin, $log_desc2);
            header("location: dashboard.php?page=schedules");
        }else{ // Postpone
            // postpone schedule
            $status = "CANCELED";
            $sched->mark_sched($sched_id, $status);

            // log changes
            $log_type2 = "SCHEDULES";
            $admin = $_SESSION['admin_name'];
            $log_desc2 = "Schedule have been canceled.";
            $logs->new_log($log_type2, $admin, $log_desc2);
            header("location: dashboard.php?page=schedules");
        }
    break;
    case 'publish-post':
        $title = $_POST['post_title'];
        $desc = $_POST['post_desc'];
        $postedBy = $_POST['postedBy'];
        $type = $_POST['type'];
        $posts->publish_post($title, $desc, $postedBy, $type);

        // tbl_log, log changes
        $log_type = "POSTS";
        $admin = $_SESSION['admin_name'];
        $log_desc = "Post have been created.";
        $logs->new_log($log_type, $admin, $log_desc);
        header("location: dashboard.php?page=posts");
    break;
    case 'modify-post':
        $id = $_POST['post_id'];
        $title = $_POST['post_title'];
        $desc = $_POST['post_desc'];
        $postedBy = $_POST['postedBy'];
        $type = $_POST['type'];
        $posts->modify_post($id, $title, $desc, $postedBy, $type);

        // tbl_log, log changes
        $log_type = "POSTS";
        $admin = $_SESSION['admin_name'];
        $log_desc = "Post have been modified.";
        $logs->new_log($log_type, $admin, $log_desc);
        header("location: dashboard.php?page=posts");
    break;
    case 'del-post':
        $id = $_GET['id'];
        $posts->delete_post($id);

        // tbl_log, log changes
        $log_type = "POSTS";
        $admin = $_SESSION['admin_name'];
        $log_desc = "Post have been deleted.";
        $logs->new_log($log_type, $admin, $log_desc);
        header("location: dashboard.php?page=posts");
    break;
    case 'new-admin':
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpass = $_POST['cpass'];
        $pass = md5($password);

        $check = $admin->check_admin($email);
        if($check == true){
            $message = "Email already exisitng!";
            header("location: dashboard.php?page=admins&message=$message");
        }else{
            if($password == $cpass){
                $admin->new_admin($name, $email, $pass);
                // tbl_log, log changes
                $log_type = "ADMINS";
                $admin = $_SESSION['admin_name'];
                $log_desc = "Admin have been created.";
                $logs->new_log($log_type, $admin, $log_desc);
                $message = "Account created.";
                header("location: dashboard.php?page=admins&message=$message");
            }else{
                $message = "Password not match. Account not created.";
                header("location: dashboard.php?page=admins&message=$message");
            }
        }
    break;
    case 'modify-admin':
        $id = $_POST['admin_id'];
        $set = $_GET['set'];

        if($set == 1){
            $name = $_POST['name'];
            $email = $_POST['email'];

            $check = $admin->check_admin($email);
            if($check == true){
                $message = "Email already exisitng!";
                header("location: dashboard.php?page=admins&message=$message");
            }else{
                $admin->edit_admin($id, $name, $email);
                // tbl_log, log changes
                $log_type = "ADMINS";
                $admin = $_SESSION['admin_name'];
                $log_desc = "Admin have been modified.";
                $logs->new_log($log_type, $admin, $log_desc);
    
                $message = "Admin Account updated.";
                header("location: dashboard.php?page=admins&message=$message");
            }
        }else{
            $pass = $_POST['pass'];
            $cpass = $_POST['cpass'];

            if($pass == $cpass){
                $pwd = md5($pass);
                
                $admin->edit_pass($id, $pwd);
                // tbl_log, log changes
                $log_type = "ADMINS";
                $admin = $_SESSION['admin_name'];
                $log_desc = "Admin have been modified.";
                $logs->new_log($log_type, $admin, $log_desc);

                $message = "Password have changed.";
                header("location: dashboard.php?page=admins&message=$message");
            }else{
                $message = "Password not match. Change Password canceled.";
                header("location: dashboard.php?page=admins&message=$message");
            }
        }
    break;
    case 'del-admin':
        $id = $_POST['admin_id'];
        $admin->delete_admin($id);

        // tbl_log, log changes
        $log_type = "ADMINS";
        $admin = $_SESSION['admin_name'];
        $log_desc = "Admin have been deleted.";
        $logs->new_log($log_type, $admin, $log_desc);

        $message = "Admin account have been deleted.";
        header("location: dashboard.php?page=admins&message=$message");
    break;
    case 'new-req':
        $type = $_POST['req_type'];
        $priest = $_POST['req_priest'];
        $client = $_POST['client_name'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $note = $_POST['note'];
        $requests->new_req($type, $priest, $client, $contact, $email, $startDate, $endDate, $startTime, $endTime, $note);

        header("location: req_appt.php");
    break;
}
?>