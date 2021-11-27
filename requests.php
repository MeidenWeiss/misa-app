<?php
include 'class.requests.php';

$requests = new Requests();

$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';

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

<body>
    <div>
        <h1><i class='bx bxs-comment-detail h_icon' ></i> Appointment Requests</h1>
        <!-- FILTER DROPDOWN -->
        <form id="filter" method="POST" action="dashboard.php?page=requests">
            <select form="filter" name="req_type" id="filter_type-dropdown">
                <option value="">Select Type</option>
                <option value="Baptism">Baptism</option>
                <option value="Confession">Confession</option>
                <option value="Holy Communion">Holy Communion</option>
                <option value="Weddings">Weddings</option>
                <option value="Renewal of Vows">Renewal of Vows</option>
                <option value="Recollection">Recollection</option>
                <option value="Funerals">Funerals</option>
                <option value="Confirmation">Confirmation</option>
                <option value="Scheduled Mass">Scheduled Mass</option>
            </select>
            <select form="filter" name="req_priest" id="filter_priest-dropdown">
                <option value="">Select Priest</option>
                <option value="">ANY</option>
                <option value="Fr. Felix P. Pasquin">Fr. Felix P. Pasquin</option>
                <option value="Fr. Gregory">Fr. Gregory</option>
                <option value="Fr. Lucas">Fr. Lucas</option>
            </select>
            <select form="filter" name="req_status" id="filter_status-dropdown">
                <option value="">Select Status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Canceled">Canceled</option>
            </select>
            <button type="submit" name="submit" value="Filter" id="filterBTN"><i class='bx bx-filter filter-icn'></i>Filter</button>
        </form>
        <form method="POST" action="dashboard.php?page=requests">
            <input class="search_box" type="text" name="client_name" autocomplete="off" placeholder="Search Client Name">
            <button type="submit" name="search" value="Search" id="searchBTN"><i class='bx bx-search search-icn'></i>Search</button>
        </form><br/>
        <!-- DATE CHECKER -->
        <form id="checker" method="POST" action="dashboard.php?page=requests">
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
        ?>
        <!-- VIEW ALL LISTING -->
        <div class="listing">
            <?php
            if (isset($_POST['submit'])) { // If Filter button is clicked
                $req_type =  $_POST['req_type'];
                $req_status =  $_POST['req_status'];
                $req_priest =  $_POST['req_priest'];
                $view_requests =  $requests->filter($req_type, $req_status, $req_priest);
            }else if(isset($_POST['search'])){ // If Search button is clicked
                $client = $_POST['client_name'];
                $view_requests = $requests->search_client($client);
            } else {
                $view_requests = $requests->view_requests(); // Query all
            }
            foreach ($view_requests as $item) {
            ?>
                <div class="list_item">
                    <button type="button" class="collapsible" data-content="<?php if(isset($item['req_id'])){}else{ echo "null";}?>">
                        <b>
                            <?php if (isset($item['req_type'])) {
                                echo $item['req_type'];
                            } else {
                                echo "Request not found.";
                            } ?>
                        </b>
                            <?php if(isset($item['req_client'])){
                                echo " - by " . ' ' . $item['req_client'];
                            }else{
                                // No result
                            }?>
                        <i class='bx bx-envelope envelope-icon-c'></i>
                        <b class="status" data-content="<?php if(isset($item['req_status'])){echo $item['req_status'];}else{ echo "EMPTY";} ?>">
                            <?php if(isset($item['req_status'])){echo $item['req_status'];}else{ echo "EMPTY";} ?>
                        </b>
                    </button>
                    <div class="content slide-in-top">
                        <b class="title-left">Appointment Type: </b><b class="text-left"><?php echo $item['req_type']; ?></b>
                        <b class="text-right"><?php $date = date_create($item['startDate']);
                                                echo date_format($date, "j F (l)"); ?></b><b class="title-right">Meeting Schedule:</b><br />
                        <b class="text-right"><?php $stime = date_create($item['startTime']);
                                                echo date_format($stime, "h:i A"); ?> - <?php $etime = date_create($item['endTime']);
                                                                                        echo date_format($etime, "h:i A"); ?></b><b class="title-right">Time: </b>
                        <b class="title-left">Requested Priest: </b><b class="text-left"><?php echo $item['req_priest']; ?></b><br />
                        <b class="status" data-content="<?php if(isset($item['req_status'])){echo $item['req_status'];}else{ echo "EMPTY";} ?>">
                            <?php if(isset($item['req_status'])){echo $item['req_status'];}else{ echo "EMPTY";} ?>
                        </b><b class="title-right">Status: </b>
                        <b class="title-left">Client Name: </b><b class="text-left"><?php echo $item['req_client']; ?></b><br />
                        <b class="title-left">Contact #: </b><b class="text-left"><?php echo $item['contact_no']; ?></b><br />
                        <b class="title-left">Email: </b><b class="text-left"><?php echo $item['client_email']; ?></b><br />
                        <b class="note-space">Note:</b><br />
                        <textarea readonly class="text-form"><?php echo $item['req_note'];?></textarea><br/>
                        <form id="form_left" method="POST" action="process.php?action=req-process&set=1" data-content="<?php if(isset($item['req_status'])){echo $item['req_status'];}else{ echo "EMPTY";} ?>">
                            <input type="hidden" name="req_id" value="<?php echo $item['req_id']; ?>">
                            <button type="submit" class="approveBTN" name="submit" value="Approve"><i class='bx bx-check-circle form-button'></i>Approve</button>
                            <a href="dashboard.php?page=adj-request&id=<?php echo $item['req_id'];?>"><button type="button" class="adjustBTN" name="submit" value="Adjust"><i class='bx bx-cog form-button'></i>Adjust</button></a>
                            <button type="submit" class="cancelBTN" name="submit" value="Cancel"><i class='bx bx-x-circle form-button'></i>Cancel</button>
                        </form>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.display == "block") {
                    content.style.display = "none";
                } else {
                    content.style.display = "block";
                }
            });
        }
    </script>
</body>

</html>