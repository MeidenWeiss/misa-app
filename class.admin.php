<?php
class Admin{
	public $db;
	
	public function __construct(){  // Establish connection to database
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE); // If line error, database is not connected/created!
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

    public function get_session(){	// Check current session
		if(isset($_SESSION['login']) && $_SESSION['login'] == true){
			return true;
		}else{
			return false;
		}
	}

    public function check_login($email,$password){	// Check login status
		$sql ="SELECT * FROM tbl_admin WHERE admin_email='$email'
					AND admin_password='$password'";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		$count_row=$result->num_rows;
		if($count_row == 1){
			$_SESSION['login']=true;
			$_SESSION['admin_name']=$row['admin_fullname'];
			return true;
		}else{
			return false;
		}
	}

	public function new_admin($name, $email, $password){
		$sql = "INSERT INTO tbl_admin(admin_fullname, admin_email, admin_password) 
				VALUES('$name', '$email', '$password');";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function get_admins(){ // Get all items
        $sql = "SELECT * FROM tbl_admin";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function get_admin($id){
        $sql = "SELECT * FROM tbl_admin WHERE admin_id = '$id'";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function check_admin($email){
        $sql = "SELECT * FROM tbl_admin WHERE admin_email = '$email'";
        $result=mysqli_query($this->db,$sql);
		$count_row=$result->num_rows;
		if($count_row == 1){
			return true;
		}else{
			return false;
		}
	}

	public function edit_admin($id, $name, $email){
		$sql = "UPDATE tbl_admin SET admin_fullname = '$name', admin_email = '$email'
		WHERE admin_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function edit_pass($id, $pwd){
		$sql = "UPDATE tbl_admin SET admin_password = '$pwd'
		WHERE admin_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function delete_admin($id){
		$sql = "DELETE FROM tbl_admin WHERE admin_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}
	
}
?>