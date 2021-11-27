<?php 
include 'config.php';
include 'class.requests.php';

$request = new Requests();
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
    <h2>Check Appointment Status</h2>
    <div class="form_container2">
        <div class="form2"><br/>
            <form id="check" method="POST" action="info.php">
            <select form="check" name="type" id="filter_type-dropdown">
                <option value="">Select Type</option>
                <option value="Baptism">Baptism</option>
                <option value="Confession">Confession</option>
                <option value="Holy Communion">Holy Communion</option>
                <option value="Weddings">Weddings</option>
                <option value="Renewal of Vows">Renewal of Vows</option>
                <option value="Recollection">Recollection</option>
                <option value="Funerals">Funerals</option>
                <option value="Confirmation">Confirmation</option>
            </select>
            <input class="input_text" type="text" name="client_name" placeholder="Client Name" required>
            <input class="input_text" type="text" name="contact" placeholder="Contact #" required>
            <input class="input_text" type="text" name="email" placeholder="sample@mail.com"><br/><br/>
            <button class="searchBTN" type="submit" name="submit" value="Search">Search</button>
            </form>
        </div>
    </div>
    <?php
        if(isset($_POST['submit'])){
            $type =  $_POST['type'];
            $client = $_POST['client_name'];
            $contact =  $_POST['contact'];
            $email =  $_POST['email'];
            $view_req = $request->search_req($type, $client, $contact, $email);
            $view_appt = $request->search_appt($type, $client, $contact, $email);
        }
        if(isset($view_req)){
            $view = $view_req;
        }else if(isset($view_appt)){
            $view = $view_appt;
        }else{
            $view = array(); // Empty
        }
        foreach($view as $item){
    ?>
    <div class="item_container" data-content="<?php if(isset($item['req_id'])){}else if(isset($item['appt_id'])){}else{echo null;}?>">
        <div class="item2"><br/>
            <b><?php if(empty($item['req_type'])){echo $item['appt_type'];}else{echo $item['req_type'];}?></b><br/>
            <b>Priest: </b><?php if(empty($item['req_priest'])){echo $item['appt_priest'];}else{echo $item['req_priest'];}?><br/><br/>
            <b>Status: </b><u class="status" data-content="<?php if(empty($item['req_status'])){echo $item['appt_status'];}else{echo $item['req_status'];}?>"><?php if(empty($item['req_status'])){echo $item['appt_status'];}else{echo $item['req_status'];}?></u><br/><br/>
            <b>Client: </b><?php if(empty($item['req_client'])){echo $item['appt_client'];}else{echo $item['req_client'];}?><br/>
            <b>Contact #: </b><?php echo $item['contact_no'];?><br/>
            <b>Email: </b><?php echo $item['client_email'];?><br/><br/>
            <h3>Schedule</h3>
            <?php 
                $sdate = date_create($item['startDate']);
                echo date_format($sdate, "M j - (D)");
            ?><br/>
            <?php $stime = date_create($item['startTime']);echo date_format($stime, "h:i A"); ?> - 
            <?php $etime = date_create($item['endTime']); echo date_format($etime, "h:i A"); ?><br/>
        </div>
    </div>
    <?php 
        }
    ?>
</body>
</html>