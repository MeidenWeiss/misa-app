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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div>
        <h1><i class='bx bx-calendar-plus h_icon'></i> Create New Schedule</h1>
        <a href="dashboard.php?page=schedules"><button type="button" id="returnBTN"><i class='bx bxs-chevron-left return-icn'></i>Go back</button></a>
        <div id="container">
            <?php 
            if(isset($_GET['message'])){
                echo $message = $_GET['message'];
            }
            ?>
            <div id="form_header">
                <b>Fields with * (asterisk) are required</b>
            </div>
            <div id="form_content">
                <form id="create" method="POST" action="process.php?action=create-sched">
                    <b class="form-title-L">Schedule Title: </b><input class="input-text-L" type="text" name="sched_title" autocomplete="off" maxlength="30">
                    <select form="create" name="event_type" id="select_type-dropdown">
                        <option value="">-- --</option>
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
                    <b class="form-title-R">Event Type *: </b><br/>
                    <b class="form-title-L clear_both" id="category-title">Category *: </b>
                    <input type="radio" id="public" name="category" value="Public" checked><label id="publicLabel" for="public">Public</label>
                    <input type="radio" id="private" name="category" value="Private"><br/><label id="privateLabel" for="private">Private</label>
                    <select form="create" name="sched_priest" id="select_priest-dropdown">
                        <option value="">Select Priest</option>
                        <option value="ANY">ANY</option>
                        <option value="Fr. Felix P. Pasquin">Fr. Felix P. Pasquin</option>
                        <option value="Fr. Gregory">Fr. Gregory</option>
                        <option value="Fr. Lucas">Fr. Lucas</option>
                    </select>
                    <b class="form-title-R">Appointed Priest *: </b></br>
                    <b class="form-client-L">Client Name: </b><input class="input-client-L" type="text" name="client_name" autocomplete="off" maxlength="50">
                    <input class="input-client-R" type="number" name="client_no" autocomplete="off" maxlength="11"><b class="form-client-R">Contact #: </b><br/>
                    <b class="form-client-L">Client Email: </b><input class="input-client-L" type="text" name="client_email" autocomplete="off" maxlength="50">
                    <br/><br/><br/><br/><br/><b class="clear_both">Event Schedule</b><br/>
                    <b class="date-title-L">Start Date *:</b><input class="date-input-L" type="date" name="startDate">
                    <input class="date-input-R" type="date" name="endDate"><b class="date-title-R">End Date *:</b>
                    <b class="date-title-L">Start Time *:</b><input class="date-input-L" type="time" name="startTime">
                    <input class="date-input-R" type="time" name="endTime"><b class="date-title-R">End Time *:</b>
                    <b class="title-left clear_both" id="note-title">Note: </b><textarea name="sched_note" form="create" class="text-form" maxlength="1000" placeholder="Max 1000 characters"></textarea>
                    <button type="submit" class="approveBTN" name="submit" value="Process"><i class='bx bx-check-circle form-button-icn'></i>Create</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>