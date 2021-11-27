<?php
class Home{
	public $db;
	
	public function __construct(){  // Establish connection to database
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE); // If line error, database is not connected/created!
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

    public function view_schedules(){ // Get all schedule items
        $now = date_create();
        $date = date_format($now, "Y-m-d");
        $sql = "SELECT * FROM tbl_schedules WHERE sched_status = 'SCHEDULED' AND startDate = '$date'";
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

    public function get_posts_pry(){
        $sql = "SELECT * FROM tbl_posts WHERE post_type = 'Prayer' ORDER BY datetime_posted DESC LIMIT 1";
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

	public function c_requests(){ // Get all pending requests items
        $sql = "SELECT COUNT(req_id) AS Total FROM tbl_requests WHERE req_status = 'PENDING'";
        $query = mysqli_query($this->db,$sql);
		$result = mysqli_fetch_assoc($query);
		if($result == null){
			return '0';
		}else{
			return $result['Total'];														// return variable created
		}
	}

	public function c_appts(){ // Get all pending requests items
        $sql = "SELECT COUNT(appt_id) AS Total FROM tbl_appointments WHERE appt_status = 'RECURRING'";
        $query = mysqli_query($this->db,$sql);
		$result = mysqli_fetch_assoc($query);
		if($result == null){
			return '0';
		}else{
			return $result['Total'];														// return variable created
		}
	}

	public function c_schedules(){ // Get all schedule items
        $sql = "SELECT COUNT(sched_id) AS Total FROM tbl_schedules WHERE sched_status = 'SCHEDULED'";
        $query = mysqli_query($this->db,$sql);
		$result = mysqli_fetch_assoc($query);
		if($result == null){
			return '0';
		}else{
			return $result['Total'];														// return variable created
		}
	}
}
?>