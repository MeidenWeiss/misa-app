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
<body>
    <h2>Daily Prayer</h2>
<?php 
    $view_posts = $post->get_posts_pry();
    foreach($view_posts as $item){
?>
    <div class="container">
        <div class="item"><br/>
            <b class="title"><?php echo $item['post_title'];?></b><br/>
            <b class="date"><?php $date = date_create($item['datetime_posted']); echo date_format($date, "F j - (D) h:i A"); ?></b><br/><br/>
            <script> 
                function getRandomInt(min, max) {
                min = Math.ceil(min);
                max = Math.floor(max);
                return Math.floor(Math.random() * (max - min)) + min; // The maximum is exclusive and the minimum is inclusive
                }

                document.write('<img id="image_size" src="images/' + getRandomInt(1, 11) + '.png">');
            </script>
            <p class="desc"><?php echo $item['post_desc'];?></p>
            <b>Posted by: </b><?php echo $item['postedBy'];?><br/><br/>
        </div>
    </div>
<?php 
    }
?>
    <h2>News & Announcements</h2>
<?php 
    $view_posts = $post->get_posts_ann();
    foreach($view_posts as $item){
?>
    <div class="container">
        <div class="item"><br/>
            <b class="title"><?php echo $item['post_title'];?></b><br/>
            <b class="date"><?php $date = date_create($item['datetime_posted']); echo date_format($date, "F j - (D) h:i A"); ?></b>
            <p class="desc"><?php echo $item['post_desc'];?></p>
            <b>Posted by: </b><?php echo $item['postedBy'];?><br/><br/>
        </div>
    </div>
    <?php 
    }
    ?>
</body>
</html>