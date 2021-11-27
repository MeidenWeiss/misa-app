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
        <?php 
            $view_admin = $admin->get_admin($id);
            foreach($view_admin as $items){
        ?>
        <form method="POST" action="process.php?action=modify-admin&set=2">
            <input type="hidden" name="admin_id" value="<?php echo $items['admin_id'];?>">
            <b class="label-title">Password: </b><input class="input_box" type="password" name="pass" placeholder="Enter Password" autocomplete="off" required><br/>
            <b class="label-title clear_both">Confirm Password: </b><input class="input_box" type="password" name="cpass" placeholder="Confirm Password" autocomplete="off" required><br/><br/><br/><br/><br/>
            <button class="createBTN" type="submit" name="submit" value="Update">Update</button>
        </form>
        <?php
            }
        ?>
    </div>
</body>
</html>