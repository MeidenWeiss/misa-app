<?php
include 'class.schedules.php';

$sched = new Schedules();

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
    <link rel="stylesheet" href="css/schedules_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css' rel='stylesheet' type='text/css'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="js/calendar.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div>
        <h1><i class='bx bx-calendar-event h_icon'></i></i> Schedules Management</h1>
        <!-- FILTER DROPDOWN -->
        <form id="filter" method="POST" action="dashboard.php?page=schedules">
            <select form="filter" name="event_type" id="filter_type-dropdown">
                <option value="">Select Type</option>
                <option value="Baptism">Baptism</option>
                <option value="Confession">Confession</option>
                <option value="Holy Communion">Holy Communion</option>
                <option value="Weddings">Weddings</option>
                <option value="Renewal of Vows">Renewal of Vows</option>
                <option value="Recollection">Recollection</option>
                <option value="Funerals">Funerals</option>
                <option value="Confirmation">Confirmation</option>
                <option value="Scheduled Mass">Scheduled Mass</option>
            </select>
            <select form="filter" name="sched_priest" id="filter_priest-dropdown">
                <option value="">Select Priest</option>
                <option value="">ANY</option>
                <option value="Fr. Felix P. Pasquin">Fr. Felix P. Pasquin</option>
                <option value="Fr. Gregory">Fr. Gregory</option>
                <option value="Fr. Lucas">Fr. Lucas</option>
            </select>
            <select form="filter" name="sched_status" id="filter_status-dropdown">
                <option value="">Select Status</option>
                <option value="SCHEDULED">Scheduled</option>
                <option value="DONE">Done</option>
                <option value="CANCELED">Canceled</option>
            </select>
            <select form="filter" name="category" id="filter_inv-dropdown">
                <option value="">Select Invitation</option>
                <option value="Private">Private</option>
                <option value="Public">Public</option>
            </select>
            <input type="date" name="startDate" id="filter_date">
            <button type="submit" name="submit" value="Filter" id="filterBTN"><i class='bx bx-filter filter-icn'></i>Filter</button>
        </form>        <a href="dashboard.php?page=new-sched"><button type="button" id="new_schedBTN"><i class='bx bx-calendar-plus newsched-icn'></i>New Schedule</button></a>
        <form method="POST" action="dashboard.php?page=schedules">
            <input id="search_box1" type="text" name="event_title" autocomplete="off" placeholder="Search Event Title">
            <input id="search_box2" type="text" name="client_name" autocomplete="off" placeholder="Search Client Name">
            <button type="submit" name="search" value="Search" id="searchBTN"><i class='bx bx-search search-icn'></i>Search</button>
        </form>
        <div id="calendar">
            <div id="calendar_header">
                <i class="icon-chevron-left"></i><h1></h1><i class="icon-chevron-right"></i>
            </div>
            <div id="calendar_weekdays"></div>
            <div id="calendar_content"></div>
        </div>
        <!-- VIEW ALL LISTING -->
        <div class="listing">
            <?php
            if (isset($_POST['submit'])) { // If Filter button is clicked
                $event_type =  $_POST['event_type'];
                $sched_status =  $_POST['sched_status'];
                $sched_priest =  $_POST['sched_priest'];
                $startDate =  $_POST['startDate'];
                $category = $_POST['category'];
                $view_sched =  $sched->filter($event_type, $sched_status, $sched_priest, $startDate, $category);
            }else if(isset($_POST['search'])){ // If Search button is clicked
                $client = $_POST['client_name'];
                $event_title = $_POST['event_title'];
                $view_sched = $sched->search($client, $event_title);
            } else {
                $view_sched = $sched->view_schedules(); // Query all
            }

            if($view_sched != null){
            foreach ($view_sched as $item) {
            ?>
                <div class="list_item">
                    <button type="button" class="collapsible" data-content="<?php if(isset($item['sched_id'])){}else{ echo "null";}?>">
                        <b>
                            <?php if (isset($item['event_type'])) {
                                echo $item['event_type'];
                            } else {
                                echo "Schedule not found.";
                            } ?>
                        </b>
                            <?php if(isset($item['client_name'])){
                                echo " - by " . ' ' . $item['client_name'];
                            }else{
                                // No result
                            }?>
                        <i class='bx bx-calendar-week calendar-icon-c'></i>
                        <b class="status" data-content="<?php if(isset($item['sched_status'])){echo $item['sched_status'];}else{ echo "EMPTY";} ?>">
                            <?php if(isset($item['sched_status'])){echo $item['sched_status'];}else{ echo "EMPTY";} ?>
                        </b>
                    </button>
                    <div class="content slide-in-top">
                        <b><u><?php echo $item['sched_title'];?></u></b><br/></br>
                        <b class="title-left">Event Type: </b><b class="text-left"><?php echo $item['event_type']; ?></b>
                        <b class="status" data-content="<?php if(isset($item['sched_status'])){echo $item['sched_status'];}else{ echo "EMPTY";} ?>">
                            <?php if(isset($item['sched_status'])){echo $item['sched_status'];}else{ echo "EMPTY";} ?>
                        </b><b class="title-right">Status: </b><br/>
                        <b class="title-left">Invitation: </b><b class="text-left"><u><?php echo strtoupper($item['category']); ?></u></b><br/>
                        <b class="title-left">Appointed Priest: </b><b class="text-left"><?php echo $item['sched_priest']; ?></b></br></br/>
                        <b>Event Schedule</b><br/></br/>
                        <b class="title-left">Start Date: </b>
                        <b class="text-left"><?php $date = date_create($item['startDate']);
                                                echo date_format($date, "F j - (l)"); ?></b><br />
                        <b class="title-left">End Date: </b>
                        <b class="text-left"><?php $date = date_create($item['endDate']);
                                                echo date_format($date, "F j - (l)"); ?></b><br />
                        <b class="title-left">Time: </b>
                        <b class="text-left"><?php $stime = date_create($item['startTime']);
                                                echo date_format($stime, "h:i A"); ?> - <?php $etime = date_create($item['endTime']);
                                                                                        echo date_format($etime, "h:i A"); ?></b><br/></br/>
                        <b class="title-left">Client Name: </b><b class="text-left"><?php echo $item['client_name']; ?></b><br />
                        <b class="title-left">Contact #: </b><b class="text-left"><?php echo $item['contact_no']; ?></b><br />
                        <b class="title-left">Email: </b><b class="text-left"><?php echo $item['client_email']; ?></b><br />
                        <b class="note-space">Note:</b><br />
                        <textarea readonly class="text-form"><?php echo $item['sched_note'];?></textarea>
                        <a data-content="<?php if(isset($item['sched_status'])){echo $item['sched_status'];}else{ echo "EMPTY";} ?>" href="dashboard.php?page=adj-sched&id=<?php echo $item['sched_id'];?>"><button type="button" class="adjustBTN" name="submit" value="Adjust"><i class='bx bx-cog form-button'></i>Adjust Schedule</button></a>
                    </div>
                </div>
            <?php
            }
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