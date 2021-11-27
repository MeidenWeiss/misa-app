<?php
$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';
$id = $_GET['id'];
if ($admin->get_session()) {
    //Do nothing...
} else {
    header("location: admin_login.php");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/admin_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div>
        <h1><i class='bx bxs-user-detail h_icon'></i> Admin Accounts Management</h1>
        <a href="dashboard.php?page=admins"><button type="button" id="returnBTN"><i class='bx bxs-chevron-left return-icn'></i>Go back</button></a>
        <b>Confirm delete admin account?</b>
        <?php 
            $view_admin = $admin->get_admin($id);
            foreach($view_admin as $items){
        ?>
        <form method="POST" action="process.php?action=del-admin">
            <input type="hidden" name="admin_id" value="<?php echo $items['admin_id'];?>">
            <b class="label-title">Full Name: </b><input class="input_box" type="text" name="name" placeholder="Enter Full Name" autocomplete="off" value="<?php echo $items['admin_fullname'];?>" disabled><br/>
            <b class="label-title clear_both">Email: </b><input class="input_box" type="text" name="email" placeholder="example@mail.com" autocomplete="off" value="<?php echo $items['admin_email'];?>" disabled><br/><br/><br/><br/><br/>
            <button class="createBTN" type="submit" name="submit" value="Yes">Yes</button>
            <a href="dashboard.php?page=admins"><button class="removeBTN" type="button" name="submit" value="No">No</button></a>
        </form>
        <?php
            }
        ?>
    </div>
</body>
</html>