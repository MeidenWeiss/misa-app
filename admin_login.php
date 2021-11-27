<?php
include 'config.php';
include 'class.admin.php';

$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';

$admin = new Admin();
$message = null;
if($admin->get_session()){
	header("location: admin_page.php");
}
if(isset($_REQUEST['submit'])){
	extract($_REQUEST);
	$login = $admin->check_login($email,md5($password));
	if($login){
		header("location: dashboard.php?page=home");
	}else{
		$message = "Invalid credentials. Please try again!";
	}
	
}
?>
<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MISA - Admin Login</title>
        <link rel="stylesheet" href="css/login_styles.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>
    <body>
        <div class="login_box">
            <form method="POST" name="login">
                <h1>ADMIN LOGIN</h1>
                <input type="text" name="email" class="inputBox" placeholder="Email" required autocomplete="off"/> <br/>
                <input type="password" name="password" class="inputBox" placeholder="Password" required/> <br/>
                <p class="msg"><?php if($message == null){}else{ echo $message;}?> </p><br/>
                <button type="submit" name="submit" class="form_btn">LOGIN</button>
            </form>
        </div>
    </body>
</html>