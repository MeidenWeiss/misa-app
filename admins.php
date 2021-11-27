<?php
$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';

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
        <?php 
            if(isset($_GET['message'])){
                $message = $_GET['message'];
                echo $message . "<br/>";
            }else{
                // Do nothing
            }
        ?>
        <div class="box_item">
            <form method="POST" action="process.php?action=new-admin">
                <b class="label-title">Full Name: </b><input class="input_box" type="text" name="name" placeholder="Enter Full Name" autocomplete="off" required>
                <b class="label-title clear_both">Email: </b><input class="input_box" type="text" name="email" placeholder="example@mail.com" autocomplete="off" required><br/>
                <b class="label-title clear_both">Password: </b><input class="input_box" type="password" name="password" placeholder="Enter Password" autocomplete="off" required><br/>
                <b class="label-title clear_both">Confirm Password: </b><input class="input_box" type="password" name="cpass" placeholder="Confirm Password" autocomplete="off" required><br/>
                <br/><br/><br/><br/><br/><button class="createBTN" type="submit" name="submit" value="Create">Create</button>
            </form>
        </div>
        <?php 
            $view_admins = $admin->get_admins();
            foreach($view_admins as $items){
        ?>
        <div class="box_item">
            <b class="title-left">Name: </b><b class="text-left"><?php echo $items['admin_fullname'];?></b><br/>
            <b class="title-left">Email: </b><b class="text-left"><?php echo $items['admin_email'];?></b><br/>
            <a href="dashboard.php?page=mod-admin&id=<?php echo $items['admin_id'];?>"><button class="modifyBTN" type="button">Modify</button></a><br/>
            <a href="dashboard.php?page=pass-admin&id=<?php echo $items['admin_id'];?>"><button class="passBTN" type="button">Change Password</button></a><br/>
            <a href="dashboard.php?page=del-admin&id=<?php echo $items['admin_id'];?>"><button class="removeBTN" type="button">Remove</button></a>
        </div>
        <?php 
            }
        ?>
    </div>
</body>
</html>