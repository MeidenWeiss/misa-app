<?php
include 'class.appointments.php';

$appts = new Appointments();

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
    <link rel="stylesheet" href="css/appt_styles.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div>
        <h1><i class='bx bxs-notepad h_icon'></i> Appointments Management</h1>
        <!-- FILTER DROPDOWN -->
        <form id="filter" method="POST" action="dashboard.php?page=appointments">
            <select form="filter" name="appt_type" id="filter_type-dropdown">
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
            <select form="filter" name="appt_priest" id="filter_priest-dropdown">
                <option value="">Select Priest</option>
                <option value="">ANY</option>
                <option value="Fr. Felix P. Pasquin">Fr. Felix P. Pasquin</option>
                <option value="Fr. Gregory">Fr. Gregory</option>
                <option value="Fr. Lucas">Fr. Lucas</option>
            </select>
            <select form="filter" name="appt_status" id="filter_status-dropdown">
                <option value="">Appt. Status</option>
                <option value="RECURRING">Recurring</option>
                <option value="DONE">Done</option>
                <option value="CANCELED">Canceled</option>
            </select>
            <select form="filter" name="pay_status" id="filter_pay-dropdown">
                <option value="">Payment Status</option>
                <option value="NOT PAID">NOT PAID</option>
                <option value="PARTIALLY PAID">PARTIALLY PAID</option>
                <option value="PAID">PAID</option>
            </select>
            <button type="submit" name="submit" value="Filter" id="filterBTN"><i class='bx bx-filter'></i>Filter</button>
        </form>
        <form method="POST" action="dashboard.php?page=appointments">
            <input class="search_box" type="text" name="client_name" autocomplete="off" placeholder="Search Client Name">
            <button type="submit" name="search" value="Search" id="searchBTN"><i class='bx bx-search search-icn'></i>Search</button>
        </form>

        <!-- VIEW ALL LISTING -->
        <div class="listing">
            <?php
            if (isset($_POST['submit'])) { // If Filter button is clicked
                $appt_type =  $_POST['appt_type'];
                $appt_status =  $_POST['appt_status'];
                $appt_priest =  $_POST['appt_priest'];
                $appt_pay =  $_POST['pay_status'];
                $view_appts =  $appts->filter($appt_type, $appt_status, $appt_priest, $appt_pay);
            }else if(isset($_POST['search'])){ // If Search button is clicked
                $client = $_POST['client_name'];
                $view_appts = $appts->search_client($client);
            }else {
                $view_appts = $appts->pending_appts(); // Query all pending
            }
            foreach ($view_appts as $item) {
            ?>
                <div class="container">
                    <a href="dashboard.php?page=view_appt&id=<?php echo $item['appt_id']; ?>">
                        <button type="button" class="list_item" data-content="<?php if (isset($item['appt_id'])) {} else {echo "null";} ?>">
                            <b>
                                <?php if (isset($item['appt_type'])) {
                                    echo $item['appt_type'];
                                } else {
                                    echo "Appointment not found.";
                                } ?>
                            </b>
                            <?php if (isset($item['appt_client'])) {
                                echo " - by " . ' ' . $item['appt_client'];
                            } else {
                                // No result
                            } ?>
                            <i class='bx bx-show view-icon'></i>
                            <b class="status" data-content="<?php if (isset($item['appt_status'])) {echo $item['appt_status'];} else {echo "EMPTY";} ?>">
                                <?php if (isset($item['appt_status'])) {
                                    echo $item['appt_status'];
                                } else {
                                    echo "EMPTY";
                                } ?>
                            </b>
                        </button>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</body>

</html>