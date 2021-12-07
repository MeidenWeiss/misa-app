<?php
include 'class.appointments.php';

$appts = new Appointments();

$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';

if ($admin->get_session()) {
    //Do nothing...
} else {
    header("location: admin_login.php");
}
$id = $_GET['id'];

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
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/appt_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div>
        <h1><i class='bx bxs-notepad h_icon'></i> Appointments Management</h1>
        <a href="dashboard.php?page=appointments"><button type="button" id="returnBTN"><i class='bx bxs-chevron-left return-icn'></i>Go back</button></a>
        <form id="checker" method="POST" action="dashboard.php?page=view_appt&id=<?php echo $id;?>">
            <b class="title-left chk_title">Date: </b> <input class="text-left chk_date" type="date" name="startDate"><br/>
            <b class="title-left clear_both">Time: </b> <input class="text-left chk_time" type="time" name="startTime"><input class="text-left chk_time" type="time" name="endTime">
            <button class="text-left chk_btn" type="submit" name="check" value="Check">Check</button>
        </form><br/><br/><br/>
        <div class="alertBox" data-content="<?php if(isset($_GET['message']) || isset($_POST['check'])){echo "message_set";}?>">
        <?php
        if(isset($_POST['check'])){
            $startDate = $_POST['startDate'];
            $startTime = $_POST['startTime'];
            $endTime = $_POST['endTime'];
            $apptResult = apptConflict($startDate, $startTime, $endTime);
            $schedResult = schedConflict($startDate, $startTime, $endTime);
            echo $apptResult . " in Appointments & " . $schedResult . " in Schedules.";
        }else{
            // Do nothing...
        }
        if(isset($_GET['message'])){
            echo $message = $_GET['message'];
        }
        ?>
        </div>
        <?php
        $view_appt = $appts->get_appt($id);

        foreach ($view_appt as $item) {
        ?><br/><br/>
        <div id="container_left">
            <div id="header_left">
                <i class='bx bx-show-alt header-icon'></i>
                <b class="header-title">Viewing Appointment</b>
            </div>
            <div id="form_left">
                <b>Meeting Schedule: </b>
                <?php $date = date_create($item['startDate']); echo date_format($date, "j F (l)"); ?><br/>
                <b>Time: </b><?php $stime = date_create($item['startTime']); echo date_format($stime, "h:i A"); ?> - <?php $etime = date_create($item['endTime']); echo date_format($etime, "h:i A"); ?><br/><br/>
                <b>Meeting Type: </b><u><?php echo $item['appt_type']; ?></u><br/><br/>
                <b class="title-left">Client: </b><b class="text-left"><?php echo $item['appt_client'];?></b>
                <b class="status" data-content="<?php echo $item['appt_status'];?>"><?php echo $item['appt_status'];?></b>
                <b class="title-right">Status: </b><br/>
                <b class="title-left">Contact #: </b><b class="text-left"><?php echo $item['contact_no'];?></b>
                <b class="pay_status" data-content="<?php echo $item['pay_status'];?>"><?php echo $item['pay_status'];?></b><br/>
                <b class="title-left">Email: </b><b class="text-left"><?php echo $item['client_email'];?></b><br/>
                <b class="title-left">Requested Priest: </b><b class="text-left"><?php echo $item['appt_priest'];?></b><br/><br/>
                <b class="title-left">Note: </b>
                <textarea class="note-text" readonly><?php echo $item['appt_note'];?></textarea><br/>
                <form id="view_appt" method="POST" action="process.php?action=process-appt" data-content="<?php if(isset($item['appt_status'])){echo $item['appt_status'];}else{ echo "EMPTY";} ?>">
                    <input id="view_appt" type="hidden" name="appt_id" value="<?php echo $item['appt_id']; ?>">
                    <button type="button" onclick="openScheduler()" class="approveBTN" name="onclick" value="Schedule"><i class='bx bx-calendar-plus form-button-icn'></i>Scheduler</button>
                    <button type="submit" class="cancelBTN" name="submit" value="Cancel"><i class='bx bx-loader form-button-icn'></i>Process</button>
                    <button type="button" onclick="openReschedule()" class="reschedBTN" name="onclick" value="Reschedule"><i class='bx bx-calendar-edit form-button-icn'></i></i>Reschedule</button>
                </form>
            </div>
        </div>
        <div id="container_right">
            <div id="header_right">
                <i class='bx bx-calendar-plus header-icon'></i>
                <b class="header-title">Scheduler</b>
            </div>
            <div id="form_right">
                <form id="scheduler" method="POST" action="process.php?action=new-schedule">
                    <b class="title-left" id="sched-title">Schedule Title:</b><input type="text" id="sched_title-text" name="sched_title" autocomplete="off" required><br/>
                    <b class="title-left" id="appt_pay-title">Appointment Payment:</b>
                    <select form="scheduler" id="pay-droplist-scheduler" name="pay_status">
                        <option value="PAID" <?php if($item['pay_status'] == 'PAID'){echo ' selected="selected"';}?>>PAID</option>
                        <option value="NOT PAID" <?php if($item['pay_status'] == 'NOT PAID'){echo ' selected="selected"';}?>>NOT PAID</option>
                    </select><br/>
                    <b class="title-left" id="event_type-title">Event Type: </b>
                    <select form="scheduler" id="event_type-dropdown" name="sched_type" class="text-left">
                        <option value="Baptism" <?php if($item['appt_type'] == 'Baptism'){echo ' selected="selected"';}?>>Baptism</option>
                        <option value="Confession" <?php if($item['appt_type'] == 'Confession'){echo ' selected="selected"';}?>>Confession</option>
                        <option value="Holy Communion" <?php if($item['appt_type'] == 'Holy Communion'){echo ' selected="selected"';}?>>Holy Communion</option>
                        <option value="Weddings" <?php if($item['appt_type'] == 'Weddings'){echo ' selected="selected"';}?>>Weddings</option>
                        <option value="Renewal of Vows" <?php if($item['appt_type'] == 'Renewal of Vows'){echo ' selected="selected"';}?>>Renewal of Vows</option>
                        <option value="Recollection" <?php if($item['appt_type'] == 'Recollection'){echo ' selected="selected"';}?>>Recollection</option>
                        <option value="Funerals" <?php if($item['appt_type'] == 'Funerals'){echo ' selected="selected"';}?>>Funerals</option>
                        <option value="Confirmation" <?php if($item['appt_type'] == 'Confirmation'){echo ' selected="selected"';}?>>Confirmation</option>
                        <option value="Scheduled Mass" <?php if($item['appt_type'] == 'Scheduled Mass'){echo ' selected="selected"';}?>>Scheduled Mass</option>
                    </select><br/>
                    <b class="title-left" id="priest-title">Appointed Priest: </b>
                    <select form="scheduler" name="sched_priest" id="priest-dropdown">
                        <option value="ANY" <?php if($item['appt_priest'] == 'ANY'){echo ' selected="selected"';}?>>ANY</option>
                        <option value="Fr. Felix P. Pasquin" <?php if($item['appt_priest'] == 'Fr. Felix P. Pasquin'){echo ' selected="selected"';}?>>Fr. Felix P. Pasquin</option>
                        <option value="Fr. Gregory" <?php if($item['appt_priest'] == 'Fr. Gregory'){echo ' selected="selected"';}?>>Fr. Gregory</option>
                        <option value="Fr. Lucas" <?php if($item['appt_priest'] == 'Fr. Lucas'){echo ' selected="selected"';}?>>Fr. Lucas</option>
                    </select><br />
                    <b class="title-left" id="category-title">Category: </b>
                    <label id="publicLabel" for="public">Public</label><input type="radio" id="public" name="category" value="Public">
                    <label id="privateLabel" for="private">Private</label><input type="radio" id="private" name="category" value="Private" checked><br/>
                    <input type="hidden" name="appt_id" value="<?php echo $item['appt_id']; ?>">
                    <input type="hidden" name="client_name" value="<?php echo $item['appt_client'];?>">
                    <input type="hidden" name="contact_no" value="<?php echo $item['contact_no'];?>">
                    <input type="hidden" name="client_email" value="<?php echo $item['client_email'];?>">
                    <b id="schedule-title">Event Schedule</b><br/>
                    <b class="title-left clear_both" id="startDate-title">Start Date: </b><input type="date" name="startDate" class="date_setter">
                    <b class="title-left clear_both" id="endDate-title">End Date: </b><input type="date" name="endDate" class="date_setter">
                    <b class="title-left clear_both" id="startTime-title">Start Time: </b><input type="time" name="startTime" class="date_setter">
                    <b class="title-left clear_both" id="endTime-title">End Time: </b><input type="time" name="endTime" class="date_setter"><br/>
                    <b class="title-left clear_both" id="note-title">Note: </b><textarea form="scheduler" name="sched_note" class="text-form" maxlength="1000" placeholder="Max 1000 characters"></textarea>
                    <button type="submit" class="approveBTN" name="submit" value="Process"><i class='bx bx-check-circle form-button-icn'></i>Process</button>
                    <button type="button" onclick="closeScheduler()" class="cancelBTN" name="onclick" value="Cancel"><i class='bx bx-x-circle form-button-icn'></i>Cancel</button>
                </form>
            </div>
        </div>
        <div id="container_extra">
            <div id="header_extra">
                <i class='bx bx-calendar-plus header-icon'></i>
                <b class="header-title">Reschedule Appointment</b>
            </div>
            <div id="form_extra">
                <form id="reschedule" method="POST"  action="process.php?action=reschedule">
                    <input type="hidden" name="appt_id" value="<?php echo $item['appt_id']; ?>">
                    <b class="title-left clear_both resched-title">Meeting Schedule: </b><input type="date" name="startDate" class="date_setter">
                    <b class="title-left clear_both resched-title">Start Time: </b><input type="time" name="startTime" class="date_setter">
                    <b class="title-left clear_both resched-title">End Time: </b><input type="time" name="endTime" class="date_setter"><br/>
                    <b class="title-left" id="priest-title">Requested Priest: </b>
                    <select form="reschedule" name="appt_priest" id="priest-dropdown">
                        <option value="ANY" <?php if($item['appt_priest'] == 'ANY'){echo ' selected="selected"';}?>>ANY</option>
                        <option value="Fr. Felix P. Pasquin" <?php if($item['appt_priest'] == 'Fr. Felix P. Pasquin'){echo ' selected="selected"';}?>>Fr. Felix P. Pasquin</option>
                        <option value="Fr. Gregory" <?php if($item['appt_priest'] == 'Fr. Gregory'){echo ' selected="selected"';}?>>Fr. Gregory</option>
                        <option value="Fr. Lucas" <?php if($item['appt_priest'] == 'Fr. Lucas'){echo ' selected="selected"';}?>>Fr. Lucas</option>
                    </select><br />
                    <b class="title-left clear_both resched-title" id="endDate-title">Note: </b><textarea name="appt_note" form="reschedule" class="text-form" maxlength="1000" placeholder="Max 1000 characters"></textarea>
                    <button type="submit" class="approveBTN" name="submit" value="Process"><i class='bx bx-check-circle form-button-icn'></i>Process</button>
                    <button type="button" onclick="closeReschedule()" class="cancelBTN" name="onclick" value="Cancel"><i class='bx bx-x-circle form-button-icn'></i>Cancel</button>
                </form>
            </div>
        </div>
        <?php 
        }
        ?>
    </div>
    <script>

        var scheduler = document.getElementById("container_right"); // Scheduler Form
        var reschedule = document.getElementById("container_extra"); // Reschedule Form

        function openScheduler(){
            scheduler.style.display = "block";
            reschedule.style.display = "none";
        }

        function closeScheduler(){
            scheduler.style.display = "none";
        }

        function openReschedule(){
            reschedule.style.display = "block";
            scheduler.style.display = "none";
        }

        function closeReschedule(){
            reschedule.style.display = "none";
        }
    </script>
</body>
</html>