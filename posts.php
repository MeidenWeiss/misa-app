<?php
include 'class.posts.php';

$posts = new Posts();

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
    <link rel="stylesheet" href="css/posts_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div>
        <h1><i class='bx bxs-paper-plane h_icon'></i> Posts Management</h1>
        <!-- FILTER DROPDOWN -->
        <form id="filter" method="POST" action="dashboard.php?page=posts">
            <select form="filter" name="post_type" id="filter_type-dropdown">
                <option value="">Select Type</option>
                <option value="Announcements">Announcements</option>
                <option value="Prayer">Prayer</option>
            </select>
            <input type="date" name="datetime" id="filter_date">
            <button type="submit" name="submit" value="Filter" id="filterBTN"><i class='bx bx-filter filter-icn'></i>Filter</button>
        </form>
        <form id="search" method="POST" action="dashboard.php?page=posts">
            <input id="search_box1" type="text" name="post_title" autocomplete="off" placeholder="Search Post Title">
            <button type="submit" name="search" value="Search" id="searchBTN"><i class='bx bx-search search-icn'></i>Search</button>
        </form>
        <!-- VIEW ALL LISTING -->
        <div class="listing">
            <div class="list_item">
                <button type="button" class="collapsible">
                    <b class="title-middle">
                        <i class='bx bx-plus add-icn'></i>
                        Create New Post
                    </b>
                </button>
                <div class="content slide-in-top">
                    <form id="create" method="POST" action="process.php?action=publish-post">
                        <b class="title-left title-L">Post Title:</b>
                        <input class="text-left text-input-L" type="text" name="post_title" autocomplete="off" required><br/>
                        <b class="title-left title-L">Posted by:</b>
                        <input class="text-left postedBy" type="text" name="postedBy" autocomplete="off" required><br/>
                        <input type="radio" id="prayer" name="type" value="Prayer"><label id="prayerLabel" for="prayer">Prayer</label>
                        <input type="radio" id="ann" name="type" value="Announcements" checked><br/><label id="annLabel" for="ann">Announcements</label><br/>
                        <b class="title-left title-L">Description:</b>
                        <textarea id="create_post" placeholder="Max 1000 Characters" form="create" name="post_desc"></textarea><br/><br/>
                        <button type="submit" class="approveBTN" name="submit" value="Publish"><i class='bx bx-check-circle form-button-icn'></i>Publish</button>
                    </form>
                </div>
            </div>
            <?php
            if (isset($_POST['submit'])) { // If Filter button is clicked
                $post_type =  $_POST['post_type'];
                $post_date =  $_POST['datetime'];
                $view_posts =  $posts->filter($post_type, $post_date);
            } else if (isset($_POST['search'])) { // If Search button is clicked
                $post_title = $_POST['post_title'];
                $view_posts = $posts->search_post($post_title);
            } else {
                $view_posts = $posts->view_posts(); // Query all pending
            }
            foreach ($view_posts as $item) {
            ?>
                <div class="list_item">
                    <button type="button" class="collapsible" data-content="<?php if (isset($item['post_id'])) {
                                                                            } else {
                                                                                echo "null";
                                                                            } ?>">
                        <b>
                            <?php if (isset($item['post_title'])) {
                                echo $item['post_title'];
                            } else {
                                echo "Post not found.";
                            } ?>
                        </b>
                        <b class="title-right"><u>
                                <?php if (isset($item['post_type'])) {
                                    echo $item['post_type'];
                                } else {
                                    echo "Others";
                                } ?>
                            </u></b>
                    </button>
                    <div class="content slide-in-top">
                        <b class="title-left">Post Title: </b><b class="text-left"><?php echo $item['post_title'];?></b><br/>
                        <b class="title-left">Post Type: </b><b class="text-left"><u><?php echo $item['post_type'];?></u></b>
                        <b class="text-right">
                            <?php $date = date_create($item['datetime_posted']); echo date_format($date, "F j - (D) h:i A"); ?>
                        </b><b class="title-right">Date Posted:</b><br/>
                        <b class="title-left">Posted by: </b><b class="text-left"><?php echo $item['postedBy'];?></b><br/><br/>
                        <b class="title-left">Description: </b>
                        <textarea class="note-text" readonly><?php echo $item['post_desc'];?></textarea>
                        <a href="dashboard.php?page=adj-post&id=<?php echo $item['post_id'];?>"><button type="button" class="adjustBTN" name="submit" value="Modify"><i class='bx bx-cog form-button-icn'></i>Modify</button></a>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.display == "block") {
                    content.style.display = "none";
                } else {
                    content.style.display = "block";
                }
            });
        }
    </script>
</body>

</html>