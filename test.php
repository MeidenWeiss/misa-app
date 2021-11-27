<?php
    include 'config.php';
    $db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    $startDate = '2021-11-12';
    $startTime = '09:00:00';
    $endTime = '11:30:00';

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

    //$start_compare = array('09:30:00', '14:30:00', '15:30:00');
    //$end_compare = array('11:00:00', '15:00:00', '16:30:00');

    $true = 0;
    $message = "";
    $countStart = count($start_compare);
    for($i = 0; $i < $countStart; $i++){
        $result = timeCheck($startTime, $start_compare[$i], $endTime, $end_compare[$i]);
        if($result != true){
            $true++;
            $st = $start_compare[$i];
            $et = $end_compare[$i];
            $message = "Conflict in " . " " . $st . " - " . $et;
        }else{
            // Do nothing
        }
    }
    if($true >= 1){
        echo $message;
    }else{
        echo "No conflict!";
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