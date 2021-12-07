<?php
class Schedules{
	public $db;
	
	public function __construct(){  // Establish connection to database
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE); // If line error, database is not connected/created!
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

	public function view_schedules(){ // Get all schedule items
        $sql = "SELECT * FROM tbl_schedules WHERE sched_status = 'SCHEDULED'";
        $query = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$result[] = $row;
		}
		if(!empty($result)){
			return $result;
		}else{
			return;																	
		}
	}

	public function get_schedules($id){ // Get all schedule items
        $sql = "SELECT * FROM tbl_schedules WHERE sched_id = '$id'";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

    public function search($client, $event_title){ // Search client name
		$sql ="SELECT * FROM tbl_schedules WHERE client_name LIKE '%$client%' AND sched_title LIKE '%$event_title%'";
		$query = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$result[] = $row;
		}
		if(!empty($result)){
			return $result;
		}else{
			return;																	
		}
	}

	public function filter($event_type, $sched_status, $sched_priest, $startDate, $category){
		$sql = "SELECT * FROM tbl_schedules WHERE event_type LIKE '%$event_type%' AND sched_status LIKE '%$sched_status%'
		AND sched_priest LIKE '%$sched_priest%' AND startDate LIKE '%$startDate%' AND category LIKE '%$category%'";
		$query = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$result[] = $row;
		}
		if(!empty($result)){
			return $result;
		}else{
			return;																	
		}
	}

	public function new_sched($title, $category, $type, $priest, $client, $contact, $email, $startDate, $startTime, $endDate, $endTime, $note){
		$sql = "INSERT INTO tbl_schedules(sched_title, category, event_type, sched_priest, client_name, contact_no, client_email, startDate, startTime, endDate, endTime, sched_note, sched_status) 
				VALUES('$title', '$category', '$type', '$priest', '$client', '$contact', '$email', '$startDate', '$startTime', '$endDate', '$endTime', '$note', 'SCHEDULED');";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function update_sched($id, $startDate, $endDate, $startTime, $endTime, $priest, $note){
		$sql = "UPDATE tbl_schedules SET startDate = '$startDate', endDate = '$endDate', startTime = '$startTime', endTime = '$endTime', sched_note = '$note', sched_priest = '$priest' WHERE sched_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function mark_sched($id, $status){
		$sql = "UPDATE tbl_schedules SET sched_status = '$status' WHERE sched_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function checkSchedDate($date){ // Search client name
		$sql ="SELECT * FROM tbl_schedules WHERE startDate = '$date'";
		$query = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$result[] = $row;
		}
		if(!empty($result)){
			return $result;
		}else{
			return null;																	
		}
	}

	public function checkApptDate($date){ // Search client name
		$sql ="SELECT * FROM tbl_appointments WHERE startDate = '$date'";
		$query = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$result[] = $row;
		}
		if(!empty($result)){
			return $result;
		}else{
			return null;																	
		}
	}
}