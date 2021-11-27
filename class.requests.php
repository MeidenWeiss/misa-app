<?php
class Requests{
    public $db;

    public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

	public function search_req($type, $client, $contact, $email){ // Search request
		$sql ="SELECT * FROM tbl_requests WHERE req_type LIKE '%$type%' AND req_client LIKE '%$client%' AND contact_no LIKE '%$contact%' AND client_email LIKE '%$email%'";
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

	public function search_appt($type, $client, $contact, $email){ // Search request
		$sql ="SELECT * FROM tbl_appointments WHERE appt_type LIKE '%$type%' AND appt_client LIKE '%$client%' AND contact_no LIKE '%$contact%' AND client_email LIKE '%$email%'";
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

	public function new_req($type, $priest, $client, $contact, $email, $startDate, $endDate, $startTime, $endTime, $note){
		$sql = "INSERT INTO tbl_requests(req_type, req_priest, req_client, contact_no, client_email, startDate, endDate, startTime, endTime, req_note, req_status) 
				VALUES('$type', '$priest', '$client', '$contact', '$email', '$startDate', '$endDate', '$startTime', '$endTime', '$note', 'PENDING');";
		$result = mysqli_query($this->db,$sql);
		return;
	}

    public function view_requests(){ // Get all pending requests items
        $sql = "SELECT * FROM tbl_requests WHERE req_status = 'PENDING'";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function get_request($id){ // Get specific item
        $sql = "SELECT * FROM tbl_requests WHERE req_id = '$id'";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function search_client($client){ // Search client name
		$sql ="SELECT * FROM tbl_requests WHERE req_client LIKE '%$client%';";
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

	public function approve_req($id){
		$sql = "UPDATE tbl_requests SET req_status = 'APPROVED' WHERE req_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function cancel_req($id){
		$sql = "UPDATE tbl_requests SET req_status = 'CANCELED' WHERE req_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function update_req($id, $type, $priest, $client, $contact, $email, $startDate, $startTime, $endDate, $endTime, $note){
		$sql = "UPDATE tbl_requests SET req_type = '$type', req_priest = '$priest', req_client = '$client', contact_no = '$contact',
		client_email = '$email', startDate = '$startDate', startTime = '$startTime', endDate = '$endDate', endTime = '$endTime', req_note = '$note'
		WHERE req_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	/* public function filter($type, $status, $priest){ // Filter
		if($type != "null" && $status == "null" && $priest == "null"){ // Type only
			$sql = "SELECT * FROM tbl_requests WHERE req_type = '$type'";
		}else if($type == "null" && $status != "null" && $priest == "null"){ // Status only
			$sql = "SELECT * FROM tbl_requests WHERE req_status = '$status'";
		}else if($type == "null" && $status == "null" && $priest != "null"){ // Priest only
			$sql = "SELECT * FROM tbl_requests WHERE req_priest = '$priest'";
		}else if($type != "null" && $status != "null" && $priest == "null"){ // Type & Status
			$sql = "SELECT * FROM tbl_requests WHERE req_type = '$type' AND req_status = '$status'";
		}else if($type == "null" && $status != "null" && $priest != "null"){ // Status & Priest
			$sql = "SELECT * FROM tbl_requests WHERE req_status = '$status' AND req_priest = '$priest'";
		}else if($type != "null" && $status == "null" && $priest != "null"){ // Type & Priest
			$sql = "SELECT * FROM tbl_requests WHERE req_type = '$type' AND req_priest = '$priest'";
		}else if($type != "null" && $status != "null" && $priest != "null"){ // ALL
			$sql = "SELECT * FROM tbl_requests WHERE req_type = '$type' AND req_status = '$status' AND req_priest = '$priest'";
		}else{
			$sql = "SELECT * FROM tbl_requests"; // Query everything
		}
		$query = mysqli_query($this->db,$sql); 				
		while($row=mysqli_fetch_assoc($query)){
			$list[] = $row;
		}
		if($list[] = null){
			return;
		}else{
			return $list;
		}
	} */

	public function filter($type, $status, $priest){
		$sql = "SELECT * FROM tbl_requests WHERE req_type LIKE '%$type%' AND req_status LIKE '%$status%' AND req_priest LIKE '%$priest%'";
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

	// GET times from TBL_APPOINTMENTS, if there's result, there is conflict
	/* public function getreqST($startDate){
		$sql ="SELECT startTime FROM tbl_appointments WHERE startDate = '$startDate' AND req_status = 'RECURRING'";
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

	public function getreqET($startDate){
		$sql ="SELECT endTime FROM tbl_appointments WHERE startDate = '$startDate' AND req_status = 'RECURRING'";
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

	// GET times from TBL_SCHEDULES
	public function getSchedTimes($startDate){
		$sql ="SELECT startTime, endTime FROM tbl_schedules WHERE startDate = '$startDate' AND sched_status = 'SCHEDULED'";
		$query = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$result[] = $row;
		}
		if(!empty($result)){
			return $result;
		}else{
			return;																	
		}
	} */
}