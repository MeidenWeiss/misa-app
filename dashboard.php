<?php
include 'config.php';
include 'class.admin.php';
include 'class.home.php';

$admin = new Admin();
$home = new Home();

$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';

if($admin->get_session()){
    //Do nothing...
}else{
    header("location: admin_login.php");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Misa App | Our Lady of Candles Parish </title>
    <link rel="stylesheet" href="css/dashboard_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class='bx bx-church icon'></i>
            <div class="logo_name">Misa App</div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="dashboard.php?page=home">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Home</span>
                </a>
                <span class="tooltip">Home</span>
            </li>
            <li>
                <a href="dashboard.php?page=requests">
                    <i class='bx bx-comment-detail'><div class="req_no"  data-content="<?php echo $req_c = $home->c_requests();?>"><p class="count"><?php echo $req_c = $home->c_requests();?></p></div></i>
                    <span class="links_name">Requests</span>
                </a>
                <span class="tooltip">Requests</span>
            </li>
            <li>
                <a href="dashboard.php?page=appointments">
                    <i class='bx bx-notepad'><div class="req_no" data-content="<?php echo $appt_c = $home->c_appts();?>"><p class="count"><?php echo $appt_c = $home->c_appts();?></p></div></i>
                    <span class="links_name">Appointments</span>
                </a>
                <span class="tooltip">Appointments</span>
            </li>
            <li>
                <a href="dashboard.php?page=schedules">
                    <i class='bx bx-calendar'><div class="req_no"  data-content="<?php echo $sched_c = $home->c_schedules();?>"><p class="count"><?php echo $sched_c = $home->c_schedules();?></p></div></i>
                    <span class="links_name">Schedules</span>
                </a>
                <span class="tooltip">Schedules</span>
            </li>
            <li>
                <a href="dashboard.php?page=posts">
                    <i class='bx bx-paper-plane'></i>
                    <span class="links_name">Posts</span>
                </a>
                <span class="tooltip">Posts</span>
            </li>
            <li>
                <a href="dashboard.php?page=admins">
                    <i class='bx bxs-user-detail'></i>
                    <span class="links_name">Admins</span>
                </a>
                <span class="tooltip">Admins</span>
            </li>
            <li>
                <a href="dashboard.php?page=logs">
                    <i class='bx bx-receipt'></i>
                    <span class="links_name">Logs</span>
                </a>
                <span class="tooltip">Logs</span>
            </li>
            <li class="profile">
                <a id="logout" href="logout.php">
                    <div class="profile-details">
                        <i class='bx bxs-user-circle'></i>
                        <div class="name_job">
                            <div class="name"><?php echo $_SESSION['admin_name'];?></div>
                        </div>
                    </div>
                    <i class='bx bx-log-out' id="log_out"></i>
                </a>
                <span class="tooltip">Admins</span>
            </li>
        </ul>
    </div>
    <section class="home-section">
        <div class="content-right">
            <?php
            switch ($page) {
                case 'home':
                    require_once 'home.php';
                    break;
                case 'requests':
                    require_once 'requests.php';
                    break;
                case 'adj-request':
                    require_once 'adj_request.php';
                    break;
                case 'appointments':
                    require_once 'appointments.php';
                    break;
                case 'view_appt':
                    require_once 'view_appt.php';
                    break;
                case 'schedules':
                    require_once 'schedules.php';
                    break;
                case 'new-sched':
                    require_once 'new-sched.php';
                    break;
                case 'adj-sched':
                    require_once 'adj_sched.php';
                    break;
                case 'posts':
                    require_once 'posts.php';
                    break;
                case 'adj-post':
                    require_once 'adj_post.php';
                    break;
                case 'admins':
                    require_once 'admins.php';
                    break;
                case 'logs':
                    require_once 'logs.php';
                    break;
                case 'mod-admin':
                    require_once 'mod_admin.php';
                break;
                case 'pass-admin':
                    require_once 'pass_admin.php';
                break;
                case 'del-admin':
                    require_once 'del_admin.php';
                break;
                default:
                    'home.php';
            }
            ?>
        </div>
    </section>
    <script>
        let sidebar = document.querySelector(".sidebar");
        let closeBtn = document.querySelector("#btn");

        closeBtn.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            menuBtnChange(); //calling the function(optional)
        });

        // following are the code to change sidebar button(optional)
        function menuBtnChange() {
            if (sidebar.classList.contains("open")) {
                closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
            } else {
                closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
            }
        }
    </script>
</body>

</html>