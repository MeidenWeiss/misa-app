<?php
include 'class.requests.php';

$requests = new Requests();

$id = $_GET['id'];

if ($admin->get_session()) {
    //Do nothing...
} else {
    header("location: admin_login.php");
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
    <link rel="stylesheet" href="css/requests_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body id="center">
    <h1><i class='bx bxs-edit h_icon'></i> Adjust Request</h1>
    <a href="dashboard.php?page=requests"><button type="button" id="returnBTN"><i class='bx bxs-chevron-left return-icn'></i>Go back</button></a>
    <!-- DATE CHECKER -->
    <form id="checker" method="POST" action="dashboard.php?page=adj-request&id=<?php echo $id;?>">
        <b>Date: </b> <input class="chk_date space1" type="date" name="startDate">
        <b>Time: </b> <input class="chk_time" type="time" name="startTime"> - <input class="chk_time" type="time" name="endTime">
        <button class="chk_btn" type="submit" name="check" value="Check">Check</button>
    </form>
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
        ?><br/><br/>
    <div id="container">
        <?php
        $view_request = $requests->get_request($id);

        foreach ($view_request as $item) {
        ?>
            <div id="form_header">
                <b id="h-left"><?php echo $item['req_type'];?></b><b id="hs-left"><?php echo " - by " . ' ' . $item['req_client']; ?></b>
                <i class='bx bx-envelope-open envelope-icon' ></i>
                <b class="status" data-content="<?php if (isset($item['req_status'])) {
                                                    echo $item['req_status'];
                                                } else {
                                                    echo "EMPTY";
                                                } ?>">
                    <?php if (isset($item['req_status'])) {
                        echo $item['req_status'];
                    } else {
                        echo "EMPTY";
                    } ?>
                </b>
            </div>
            <div id="form_content">
                <form id="adjust" method="POST" action="process.php?action=req-process&set=2">
                    <input type="hidden" name="req_id" value="<?php echo $item['req_id'];?>">
                    <b class="title-left">Appointment Type: </b>
                    <select form="adjust" name="req_type" class="text-left">
                        <option value="Baptism" <?php if($item['req_type'] == 'Baptism'){echo ' selected="selected"';}?>>Baptism</option>
                        <option value="Confession" <?php if($item['req_type'] == 'Confession'){echo ' selected="selected"';}?>>Confession</option>
                        <option value="Holy Communion" <?php if($item['req_type'] == 'Holy Communion'){echo ' selected="selected"';}?>>Holy Communion</option>
                        <option value="Weddings" <?php if($item['req_type'] == 'Weddings'){echo ' selected="selected"';}?>>Weddings</option>
                        <option value="Renewal of Vows" <?php if($item['req_type'] == 'Renewal of Vows'){echo ' selected="selected"';}?>>Renewal of Vows</option>
                        <option value="Recollection" <?php if($item['req_type'] == 'Recollection'){echo ' selected="selected"';}?>>Recollection</option>
                        <option value="Funerals" <?php if($item['req_type'] == 'Funerals'){echo ' selected="selected"';}?>>Funerals</option>
                        <option value="Confirmation" <?php if($item['req_type'] == 'Confirmation'){echo ' selected="selected"';}?>>Confirmation</option>
                        <option value="Scheduled Mass" <?php if($item['req_type'] == 'Scheduled Mass'){echo ' selected="selected"';}?>>Scheduled Mass</option>
                    </select>
                    <input type="date" class="text-right" name="startDate" value="<?php echo $item['startDate'];?>">
                    <b class="title-right bot_margin">Meeting Schedule:</b><br />
                    <input type="time" class="text-right clear_both" name="endTime" value="<?php echo $item['endTime'];?>">
                    <input type="time" class="text-right" name="startTime" value="<?php echo $item['startTime'];?>">
                    <b class="title-right">Time: </b>
                    <b class="title-left">Requested Priest: </b>
                    <select form="adjust" name="req_priest" class="text-left bot_margin">
                        <option value="ANY" <?php if($item['req_priest'] == 'ANY'){echo ' selected="selected"';}?>>ANY</option>
                        <option value="Fr. Felix P. Pasquin" <?php if($item['req_priest'] == 'Fr. Felix P. Pasquin'){echo ' selected="selected"';}?>>Fr. Felix P. Pasquin</option>
                        <option value="Fr. Gregory" <?php if($item['req_priest'] == 'Fr. Gregory'){echo ' selected="selected"';}?>>Fr. Gregory</option>
                        <option value="Fr. Lucas" <?php if($item['req_priest'] == 'Fr. Lucas'){echo ' selected="selected"';}?>>Fr. Lucas</option>
                    </select><br />
                    <b class="title-left clear_both">Client Name: </b><input class="text-left" name="client" type="text" value="<?php echo $item['req_client']; ?>"><br />
                    <b class="title-left clear_both">Contact #: </b><input class="text-left" name="contact" type="text" value="<?php echo $item['contact_no']; ?>"><br />
                    <b class="title-left clear_both">Email: </b><input class="text-left" name="email" type="text" value="<?php echo $item['client_email']; ?>"><br />
                    <b class="note-space clear_both">Note:</b><br />
                    <textarea form="adjust" name="note" class="text-form" placeholder="Max 1000 Characters"><?php echo $item['req_note'];?></textarea>
                    <button type="submit" class="approveBTN" name="submit" value="Approve"><i class='bx bx-check-circle form-button'></i>Approve</button>
                    <a href="dashboard.php?page=requests"><button type="button" class="cancelBTN" name="submit"><i class='bx bx-x-circle form-button'></i>Cancel</button></a>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
</body>

</html>