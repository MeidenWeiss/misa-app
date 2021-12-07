<?php
include 'class.posts.php';

$posts = new Posts();

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
    <link rel="stylesheet" href="css/posts_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body id="center">
    <h1><i class='bx bxs-edit h_icon'></i> Modify Post</h1>
    <a href="dashboard.php?page=posts"><button type="button" id="returnBTN"><i class='bx bxs-chevron-left return-icn'></i>Go back</button></a>
    <div id="container">
        <?php
        $view_post = $posts->get_post($id);

        foreach ($view_post as $item) {
        ?>
        <div id="form_header">
            
        </div>
        <div id="form_content">
            <form id="modify" method="POST" action="process.php?action=modify-post">
                <input type="hidden" name="post_id" value="<?php echo $item['post_id'];?>">
                <b class="title-left title-L">Post Title:</b>
                <input class="text-left text-input-L" type="text" name="post_title" autocomplete="off" value="<?php echo $item['post_title'];?>" required><br/>
                <b class="title-left title-L">Posted by:</b>
                <input class="text-left postedBy" type="text" name="postedBy" autocomplete="off" value="<?php echo $item['postedBy'];?>" required><br/>
                <input type="radio" id="prayer" name="type" value="Prayer" <?php if($item['post_type'] == 'Prayer'){echo 'checked';}?>><label id="prayerLabel" for="prayer">Prayer</label>
                <input type="radio" id="ann" name="type" value="Announcements" <?php if($item['post_type'] == 'Announcements'){echo 'checked';}?>><br/><label id="annLabel" for="ann">Announcements</label><br/>
                <b class="title-left title-L">Description:</b>
                <textarea id="modify_post" placeholder="Max 1000 Characters" form="modify" name="post_desc"><?php echo $item['post_desc'];?></textarea><br/><br/>
                <button type="submit" class="approveBTN" name="submit" value="Update"><i class='bx bx-check-circle form-button'></i>Update</button>
                <a href="dashboard.php?page=posts"><button type="button" class="cancelBTN" name="submit"><i class='bx bx-x-circle form-button'></i>Cancel</button></a>
            </form>
        </div>
        <div id="confirmDEL"><br/>
            <b class="confirm-txt">Confirm delete post?</b><br/><br/>
            <a href="process.php?action=del-post&id=<?php echo $item['post_id'];?>"><button type="button" class="checkBTN" name="submit"><i class='bx bx-check del-icons'></i></button></a>
            <button type="button" class="xBTN" name="submit" onclick="cancelDEL()"><i class='bx bx-x del-icons'></i></button>
        </div>
        <?php 
        }
        ?>
    </div>
    <script>
        function confirmDEL() {
            document.getElementById("confirmDEL").style.display = "block";
        }

        function cancelDEL(){
            document.getElementById("confirmDEL").style.display = "none";
        }
    </script>
</body>