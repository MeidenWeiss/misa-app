<?php

$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';

if ($admin->get_session()) {
    //Do nothing...
} else {
    header("location: admin_login.php");
}

$events = $home->view_schedules();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/home_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h1><i class='bx bxs-grid-alt h_icon'></i> Homepage</h1>
    <div class="container_M">
        <div class="container_L">
            <h2>Current Events</h2><br/>
            <p id="text_info" data-content="<?php if($events != null){}else{echo "null";}?>">No events happening today...</p>
            <?php 
                if($events != null){
                foreach($events as $item){
            ?>
            <div class="e_item">
                <b class="text_center"><?php echo $item['sched_title'];?></b><br/>
                <?php 
                    $st = date_create($item['startTime']); 
                    $et = date_create($item['endTime']);
                    $start = date_format($st, "h:i (A)");
                    $end = date_format($et, "h:i (A)");
                    echo $start . " - " . $end;
                ?><br/><br/>
                Priest: <u><?php echo $item['sched_priest'];?></u>
            </div>
            <?php
                }
            }
            ?>
        </div>
            <?php 
                $prayer = $home->get_posts_pry();
                foreach($prayer as $item){
            ?>
        <div class="container_R">
            <h2>Prayer of the Day</h2>
            <textarea class="prayer" readonly><?php echo $item['post_desc'];?></textarea>
        </div>
            <?php
                }
            ?>
    </div>
</body>
</html>