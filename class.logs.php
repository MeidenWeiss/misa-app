<?php
class Logs{
	public $db;
	
	public function __construct(){  // Establish connection to database
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE); // If line error, database is not connected/created!
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

	public function view_logs(){ // Get all log items
        $sql = "SELECT * FROM tbl_log ORDER BY log_date DESC";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

    public function filter($log_type, $log_date){
		$sql = "SELECT * FROM tbl_log WHERE log_type LIKE '%$log_type%' AND log_date LIKE '%$log_date%'";
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

	public function new_log($log_type, $admin, $log_desc){
		$sql = "INSERT INTO tbl_log(log_type, admin_name, log_date, log_desc) 
				VALUES('$log_type', '$admin', NOW(), '$log_desc');";
		$result = mysqli_query($this->db,$sql);
		return;
	}
}