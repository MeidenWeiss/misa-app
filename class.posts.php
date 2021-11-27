<?php
class Posts{
	public $db;
	
	public function __construct(){  // Establish connection to database
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE); // If line error, database is not connected/created!
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

	public function view_posts(){ // Get all post items
        $sql = "SELECT * FROM tbl_posts";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function get_post($id){ // Get a post item
        $sql = "SELECT * FROM tbl_posts WHERE post_id = '$id'";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function publish_post($title, $desc, $postedBy, $type){
		$sql = "INSERT INTO tbl_posts(post_title, post_desc, datetime_posted, postedBy, post_type) 
				VALUES('$title', '$desc', NOW(), '$postedBy', '$type');";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function filter($post_type, $startDate){
		$sql = "SELECT * FROM tbl_posts WHERE post_type LIKE '%$post_type%' AND datetime_posted LIKE '%$startDate%'";
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

	public function search_post($post_title){ // Search post title
		$sql ="SELECT * FROM tbl_posts WHERE post_title LIKE '%$post_title%'";
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

	public function modify_post($id, $title, $desc, $postedBy, $type){
		$sql = "UPDATE tbl_posts SET post_title = '$title', post_desc = '$desc', postedBy = '$postedBy', post_type = '$type'
		WHERE post_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function delete_post($id){
		$sql = "DELETE FROM tbl_posts WHERE post_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		return;
	}

	public function get_posts_ann(){
        $sql = "SELECT * FROM tbl_posts WHERE post_type = 'Announcements' ORDER BY datetime_posted DESC LIMIT 3";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function get_posts_pry(){
        $sql = "SELECT * FROM tbl_posts WHERE post_type = 'Prayer' ORDER BY datetime_posted DESC LIMIT 1";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function viewing(){ // Get all schedule items
        $sql = "SELECT * FROM tbl_schedules WHERE sched_status = 'SCHEDULED' ORDER BY startDate DESC LIMIT 6";
        $result=mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}
}