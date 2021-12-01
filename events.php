<?php 
include 'config.php';
include 'class.posts.php';

$post = new Posts();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Misa App | Our Lady of Candles Parish </title>
    <link rel="stylesheet" href="css/public_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php 
    $view_posts = $post->viewing();
    foreach($view_posts as $item){
?>
<body>
    <div class="container">
        <div class="item"><br/>
            <b>
            <?php 
            if($item['category'] == "Private"){
                echo "Private Event";
            }else{
                echo $item['sched_title'];
            }
            ?></b><br/><br/>
            <?php 
            $sdate = date_create($item['startDate']); 
            $edate = date_create($item['endDate']); 
            $startDate = date_format($sdate, "M j - (D)");
            $endDate = date_format($edate, "M j - (D)"); 
            
            if($sdate == $edate){
                echo date_format($sdate, "M j - (l)");
            }else{
                echo $startDate . " to " . $endDate;
            }
            ?><br/>
            <?php $stime = date_create($item['startTime']);echo date_format($stime, "h:i A"); ?> - 
            <?php $etime = date_create($item['endTime']); echo date_format($etime, "h:i A"); ?><br/>
            <textarea class="ann" readonly>
            <?php 
            if($item['category'] == "Private"){
                // Empty
            }else{
                echo $item['sched_note'];
            }
            ?>
            </textarea>
            <b class="title-left">Appointed Priest: </b><?php echo $item['sched_priest'];?><br/><br/>
        </div>
    </div>
</body>
<?php 
    }
?>
</html>
