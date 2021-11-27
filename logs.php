<?php
include 'class.logs.php';

$logs = new Logs();

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
    <link rel="stylesheet" href="css/logs_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div>
        <h1><i class='bx bxs-receipt h_icon' ></i> Logs</h1>
        <!-- FILTER DROPDOWN -->

        
        <div class="container">
            <table>
                <tr>
                    <th>Log Date</th>
                    <th>Log Type</th>
                    <th>Action</th>
                    <th>Admin</th>
                </tr>
        <?php
            if (isset($_POST['submit'])) { // If Filter button is clicked
                $log_type =  $_POST['log_type'];
                $log_date =  $_POST['log_date'];
                $view_logs =  $logs->filter($log_type, $log_date);
            }else {
                $view_logs = $logs->view_logs(); // Query all pending
            }
            foreach ($view_logs as $item) {
        ?>
                <tr>
                    <td><?php $date = date_create($item['log_date']); echo date_format($date, "j M Y (D) - h:i A"); ?></td>
                    <td><?php echo $item['log_type'];?></td>
                    <td><?php echo $item['log_desc'];?></td>
                    <td><?php echo $item['admin_name'];?></td>
                </tr>
        <?php 
            }
        ?>
            </table>
        </div>
    </div>
</body>