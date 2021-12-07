<?php
include 'config.php';
include 'class.schedules.php';

$sched = new Schedules();
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
    <h2>Request Appointment Form</h2>
    <div class="sched_box" data-content="<?php if (isset($_POST['check'])) {
                                                echo "INFO";
                                            } ?>">
        <h3>Scheduled Appointments</h3>
        <?php
        if(isset($_POST['check'])){
            $date = $_POST['checkDate'];
            $result1 = $sched->checkApptDate($date);
            $result2 = $sched->checkSchedDate($date);
        ?>
        <table class="minimalistBlack">
            <thead>
                <tr>
                    <td>Date</td>
                    <td>Time</td>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result1 != null) {
                    foreach ($result1 as $item) {
                        $st =  date_create($item['startTime']);
                        $et =  date_create($item['endTime']);
                        $startTime = date_format($st, "h:i A");
                        $endTime = date_format($et, "h:i A");

                        $sd = date_create($item['startDate']);
                        $ed = date_create($item['endDate']);
                        $startDate = date_format($sd, "M j, Y");
                        $endDate = date_format($ed, "M j, Y");
                ?>
                        <tr>
                            <td><?php echo $startDate . " - " . $endDate; ?></td>
                            <td><?php echo $startTime . " - " . $endTime; ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <h3>Scheduled Events</h3>
        <table class="minimalistBlack">
            <thead>
                <tr>
                    <td>Date</td>
                    <td>Time</td>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result2 != null) {
                    foreach ($result2 as $item) {
                        $st =  date_create($item['startTime']);
                        $et =  date_create($item['endTime']);
                        $startTime = date_format($st, "h:i A");
                        $endTime = date_format($et, "h:i A");

                        $sd = date_create($item['startDate']);
                        $ed = date_create($item['endDate']);
                        $startDate = date_format($sd, "M j, Y");
                        $endDate = date_format($ed, "M j, Y");
                ?>
                        <tr>
                            <td><?php echo $startDate . " - " . $endDate; ?></td>
                            <td><?php echo $startTime . " - " . $endTime; ?></td>
                        </tr>
                <?php
                    }
                }
            }
                ?>
            </tbody>
        </table>
    </div>
    <form method="POST" action="req_appt.php">
        Check Date: <input class="input_time" type="date" name="checkDate">
        <button class="searchBTN" type="submit" name="check" value="Check">Check</button>
    </form>
    <div class="form_container">
        <div class="form"><br />
            <form id="create" method="POST" action="process.php?action=new-req">
                <select form="create" name="req_type" id="filter_type-dropdown">
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
                <select form="create" name="req_priest" id="filter_priest-dropdown">
                    <option value="">Select Priest</option>
                    <option value="ANY">ANY</option>
                    <option value="Fr. Felix P. Pasquin">Fr. Felix P. Pasquin</option>
                    <option value="Fr. Gregory">Fr. Gregory</option>
                    <option value="Fr. Lucas">Fr. Lucas</option>
                </select>
                <input class="input_text" type="text" name="client_name" placeholder="Client Name" required>
                <input class="input_text" type="text" name="contact" placeholder="Contact #" required>
                <input class="input_text" type="text" name="email" placeholder="sample@mail.com"><br /><br />
                <h3>Set Schedule</h3>
                Date: <input class="input_time" type="date" name="Date"><br />
                Start Time: <input class="input_time" type="time" name="startTime"><br />
                End Time: <input class="input_time" type="time" name="endTime"><br /><br />
                <h3>Additional Notes</h3>
                <textarea form="create" name="note" class="text-form"></textarea>
                <button class="approveBTN" type="submit" name="submit" value="Process">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>