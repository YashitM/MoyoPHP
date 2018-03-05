<?php include 'base.php' ?>

<?php startblock('content') ?>
<?php
    if(!isset($_SESSION['logincust'])) {
        header("Location: login.php");
        exit();
    }
?>


<div class="container padded-container">
    <form class="form-login" method="post" action="#" id="offerrideform">
        <div class="input-group">
            <input type="hidden" name="sou_lati" id="id_sou_lati" />
        </div>
        <div class="input-group">
            <input type="hidden" name="sou_long" id="id_sou_long" />
        </div>
        <div class="input-group">
            <input type="hidden" name="des_lati" id="id_des_lati" />
        </div>
        <div class="input-group">
            <input type="hidden" name="des_long" id="id_des_long" />
        </div>
        <div class="form-log-in-with-email">
            <div class="form-white-background">
                <div class="form-title-row">
                    <h1>Offer Ride</h1>
                </div>
                <div class="form-row">
                    <label>
                        <span>Car Model</span>
                        <input type="text" id="id_car_model" placeholder="Enter Car Model" name="car_model">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Seats</span>
                        <input type="text" id="id_seats" placeholder="Enter No. of Seats" name="seats">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Seats Available</span>
                        <input type="text" id="id_seats_available" placeholder="Enter No. of Seats Available" name="seats_available">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Cost</span>
                        <input type="text" id="id_cost" placeholder="Enter Cost" name="cost">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Start Time</span>
                        <input type="time" id="id_start_time" placeholder="Enter Start Time" name="start_time">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Date Of Ride</span>
                        <input type="date" id="id_dateofride" name="dateofride">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Message</span>
                        <textarea name="message" placeholder="Enter Message" id="id_message"></textarea>
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Source Location</span>
                        <input type="text" name="source_location" maxlength="1000" required="" placeholder="Select Location Below" id="id_source_location">
                    </label>
                </div>
                <button type="button" id="source_location_button" style="margin-top: -20px;" data-toggle="modal" data-target="#myModal">Source</button>
                <div class="form-row">
                    <label>
                        <span>Destination Location</span>
                        <input type="text" name="destination_location" maxlength="1000" required="" placeholder="Select Location Below" id="id_destination_location">
                    </label>
                </div>
                <button type="button" id="destination_location_button" style="margin-top: -20px;" data-toggle="modal" data-target="#myModal">Destination</button>
                <div class="form-row">
                    <button type="submit" class="btn">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Select Location</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="pac-card" id="pac-card">
                    <div id="pac-container">
                        <input id="location_input" type="text" placeholder="Enter a location">
                    </div>
                </div>
                <div id="map"></div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<?php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ( !(!isset($_POST['sou_lati']) || trim($_POST['sou_lati']) == '') &&
        !(!isset($_POST['sou_long']) || trim($_POST['sou_long']) == '') &&
        !(!isset($_POST['des_lati']) || trim($_POST['des_lati']) == '') &&
        !(!isset($_POST['des_long']) || trim($_POST['des_long']) == '')
    ) {
        if (!(!isset($_POST['car_model']) || trim($_POST['car_model']) == '') &&
            !(!isset($_POST['seats']) || trim($_POST['seats']) == '') &&
            !(!isset($_POST['seats_available']) || trim($_POST['seats_available']) == '') &&
            !(!isset($_POST['cost']) || trim($_POST['cost']) == '') &&
            !(!isset($_POST['start_time']) || trim($_POST['start_time']) == '') &&
            !(!isset($_POST['dateofride']) || trim($_POST['dateofride']) == '') &&
            !(!isset($_POST['message']) || trim($_POST['message']) == '') &&
            !(!isset($_POST['source_location']) || trim($_POST['source_location']) == '') &&
            !(!isset($_POST['destination_location']) || trim($_POST['destination_location']) == '')
        ) {
            $sou_lati = $_POST['sou_lati'];
            $sou_long = $_POST['sou_long'];
            $des_lati = $_POST['des_lati'];
            $des_long = $_POST['des_long'];
            $car_model = $_POST['car_model'];
            $seats = $_POST['seats'];
            $seats_available = $_POST['seats_available'];
            $cost = $_POST['cost'];
            $start_time = $_POST['start_time'];
            $dateofride = $_POST['dateofride'];
            $message = $_POST['message'];
            $source_location = $_POST['source_location'];
            $destination_location = $_POST['destination_location'];

            require_once("libs/API/DbHandler.php");
            $db = new DbHandler();
            $db->createRide($_SESSION['oauth_uid'], $car_model, $seats, $cost, $sou_lati, $sou_long, $des_lati, $des_long, $start_time, $source_location, $destination_location, $dateofride, $message);
        }
        else {
            echo "<script>
                $.notify({
                    message: 'Please Complete All Fields',
                    type: 'success'
                });
            </script>";
        }
    }
    else {
        echo "<script>
                $.notify({
                    message: 'Some error occurred. Please try again later.',
                    type: 'success'
                });
            </script>";
    }
}
?>

<?php endblock() ?>