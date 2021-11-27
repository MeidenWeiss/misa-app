<?php
include 'class.schedules.php';

$sched = new Schedules();

$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';
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
    <link rel="stylesheet" href="css/schedules_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div>
        <h1><i class='bx bx-calendar-edit h-icon'></i> Adjust Schedule</h1>
        <a href="dashboard.php?page=schedules"><button type="button" id="returnBTN"><i class='bx bxs-chevron-left return-icn'></i>Go back</button></a>
        <!-- DATE CHECKER -->
        <form id="checker" method="POST" action="dashboard.php?page=adj-sched&id=<?php echo $id;?>">
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
                if(isset($_GET['message'])){
                    echo $message = $_GET['message'];
                }
            ?><br/><br/>
        <?php
        $view_sched = $sched->get_schedules($id);

        foreach ($view_sched as $item) {
        ?>
            <div id="container">
                <div id="form_header">
                    <b class="title-left">
                        <?php if (isset($item['event_type'])) {
                            echo $item['event_type'];
                        } else {
                            echo "Schedule not found.";
                        } ?>
                    </b>
                    <b class="text-left">
                        <?php if (isset($item['client_name'])) {
                            echo " - by " . ' ' . $item['client_name'];
                        } else {
                            // No result
                        } ?>
                    </b>
                    <i class='bx bx-calendar-week calendar-icon-c'></i>
                    <b class="status" data-content="<?php if (isset($item['sched_status'])) {
                                                        echo $item['sched_status'];
                                                    } else {
                                                        echo "EMPTY";
                                                    } ?>">
                        <?php if (isset($item['sched_status'])) {
                            echo $item['sched_status'];
                        } else {
                            echo "EMPTY";
                        } ?>
                    </b>
                </div>
                <div id="form_content">
                    <form id="adjust" method="POST" action="process.php?action=adj-sched">
                        <input type="hidden" name="sched_id" value="<?php echo $item['sched_id'];?>">
                        <b>Event Schedule</b><br />
                        <b class="date-title-L">Start Date: </b><input class="date-input-L" type="date" name="startDate" value="<?php echo $item['startDate'];?>">
                        <input class="date-input-R" type="date" name="endDate" value="<?php echo $item['endDate'];?>"><b class="date-title-R">End Date: </b><br/>
                        <b class="date-title-L">Start Time: </b><input class="date-input-L" type="time" name="startTime" value="<?php echo $item['startTime'];?>">
                        <input class="date-input-R" type="time" name="endTime" value="<?php echo $item['endTime'];?>"><b class="date-title-R">End Time: </b><br/>
                        <b class="date-title-L">Appointed Priest: </b><br/>
                        <select form="adjust" name="sched_priest" id="filter2_priest-dropdown">
                            <option value="ANY" <?php if($item['sched_priest'] == 'ANY'){echo ' selected="selected"';}?>>ANY</option>
                            <option value="Fr. Felix P. Pasquin" <?php if($item['sched_priest'] == 'Fr. Felix P. Pasquin'){echo ' selected="selected"';}?>>Fr. Felix P. Pasquin</option>
                            <option value="Fr. Gregory" <?php if($item['sched_priest'] == 'Fr. Gregory'){echo ' selected="selected"';}?>>Fr. Gregory</option>
                            <option value="Fr. Lucas" <?php if($item['sched_priest'] == 'Fr. Lucas'){echo ' selected="selected"';}?>>Fr. Lucas</option>
                        </select><br/>
                        <b class="date-title-L">Note: </b><textarea name="sched_note" form="adjust" class="text-form" placeholder="Max 1000 Characters"><?php echo $item['sched_note'];?></textarea><br/><br/>
                        <button type="submit" class="approveBTN" name="adjust" value="Adjust"><i class='bx bx-check-circle form-button-icn'></i>Adjust</button>
                        <button type="submit" class="doneBTN" name="adjust" value="Done"><i class='bx bx-check-circle form-button-icn'></i>Done</button>
                        <button type="submit" class="cancelBTN" name="adjust" value="Cancel"><i class='bx bx-x-circle form-button-icn'></i>Postpone</button>
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</body>

</html>