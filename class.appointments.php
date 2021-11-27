<?php
class Appointments{
    public $db;

    public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

	public function pending_appts(){ // Get all pending requests items
        $sql = "SELECT * FROM tbl_appointments WHERE appt_status = 'RECURRING'";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($result)){
			return $result;
		}else{
			return;																	
		}
	}

	public function get_appt($id){ // Get specific item
        $sql = "SELECT * FROM tbl_appointments WHERE appt_id = '$id'";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function search_client($client){ // Search client name
		$sql ="SELECT * FROM tbl_appointments WHERE appt_client LIKE '%$client%';";
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

	public function reschedule($id, $startDate, $startTime, $endTime, $priest, $note){
		$sql = "UPDATE tbl_appointments SET startDate = '$startDate', endDate = '$startDate', startTime = '$startTime', endTime = '$endTime', appt_priest = '$priest', appt_note = '$note' 
		WHERE appt_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function update_appt($id){
		$sql = "UPDATE tbl_appointments SET appt_status = 'DONE' WHERE appt_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function update_pay($id, $pay){
		$sql = "UPDATE tbl_appointments SET pay_status = '$pay' WHERE appt_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function new_appt($type, $priest, $client, $contact, $email, $startDate, $startTime, $endDate, $endTime, $note){
		$sql = "INSERT INTO tbl_appointments(appt_type, appt_priest, appt_client, contact_no, client_email, startDate, startTime, endDate, endTime, appt_note, appt_status, pay_status) 
				VALUES('$type', '$priest', '$client', '$contact', '$email', '$startDate', '$startTime', '$endDate', '$endTime', '$note', 'RECURRING', 'NOT PAID');";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function cancel_appt($id, $status, $pay){
		$sql = "UPDATE tbl_appointments SET appt_status = '$status', pay_status = '$pay'
		WHERE appt_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function filter($type, $status, $priest, $pay){
		$sql = "SELECT * FROM tbl_appointments WHERE appt_type LIKE '%$type%' AND appt_status LIKE '%$status%'
		AND appt_priest LIKE '%$priest%' AND pay_status LIKE '%$pay%'";
		$query = mysqli_query($this->db,$sql); 				
		while($row=mysqli_fetch_assoc($query)){
			$list[] = $row;
		}
		if($list[] = null){
			return;
		}else{
			return $list;
		}
	}
	
	/* public function filter($type, $status, $priest, $pay){ // Filter
		if($type != "null" && $status == "null" && $priest == "null" && $pay == "null"){ // Type only
			$sql = "SELECT * FROM tbl_appointments WHERE appt_type = '$type'";
		}else if($type == "null" && $status != "null" && $priest == "null" && $pay == "null"){ // Status only
			$sql = "SELECT * FROM tbl_appointments WHERE appt_status = '$status'";
		}else if($type == "null" && $status == "null" && $priest != "null" && $pay == "null"){ // Priest only
			$sql = "SELECT * FROM tbl_appointments WHERE appt_priest = '$priest'";
		}else if($type != "null" && $status != "null" && $priest == "null" && $pay == "null"){ // Type & Status
			$sql = "SELECT * FROM tbl_appointments WHERE appt_type = '$type' AND appt_status = '$status'";
		}else if($type == "null" && $status != "null" && $priest != "null" && $pay == "null"){ // Status & Priest
			$sql = "SELECT * FROM tbl_appointments WHERE appt_status = '$status' AND appt_priest = '$priest'";
		}else if($type != "null" && $status == "null" && $priest != "null" && $pay == "null"){ // Type & Priest
			$sql = "SELECT * FROM tbl_appointments WHERE appt_type = '$type' AND appt_priest = '$priest'";
		}else if($type != "null" && $status == "null" && $priest == "null" && $pay != "null"){ // Type & Pay
			$sql = "SELECT * FROM tbl_appointments WHERE appt_type = '$type' AND pay_status = '$pay'";
		}else if($type == "null" && $status != "null" && $priest == "null" && $pay != "null"){ // Status & Pay
			$sql = "SELECT * FROM tbl_appointments WHERE appt_status = '$status' AND pay_status = '$pay'";
		}else if($type == "null" && $status == "null" && $priest != "null" && $pay != "null"){ // Priest & Pay
			$sql = "SELECT * FROM tbl_appointments WHERE appt_priest = '$priest' AND pay_status = '$pay'";
		}else if($type == "null" && $status == "null" && $priest == "null" && $pay != "null"){ // Pay only
			$sql = "SELECT * FROM tbl_appointments WHERE pay_status = '$pay'";
		}else if($type != "null" && $status != "null" && $priest != "null" && $pay != "null"){ // ALL
			$sql = "SELECT * FROM tbl_appointments WHERE appt_type = '$type' AND appt_status = '$status' AND appt_priest = '$priest' AND pay_status = '$pay'";
		}else{
			$sql = "SELECT * FROM tbl_appointments"; // Query everything
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
}