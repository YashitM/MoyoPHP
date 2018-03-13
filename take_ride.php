<?php include 'base.php' ?>

<?php startblock('content') ?>

    <div class="container padded-container">
        <form class="form-login" method="post" action="" id="offerrideform">
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
                        <h1>Take Ride</h1>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Date Of Ride*</span>
                            <input type="date" id="id_dateofride" name="dateofride" placeholder="Date of Ride">
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Source Location*</span>
                            <input type="text" name="source_location" maxlength="1000" required="" placeholder="Select Location Below" id="id_source_location">
                        </label>
                    </div>
                    <button type="button" id="source_location_button_take_ride" style="margin-top: -20px;" data-toggle="modal" data-target="#myModal2">Source</button>
                    <div class="form-row">
                        <label>
                            <span>Destination Location*</span>
                            <input type="text" name="destination_location" maxlength="1000" required="" placeholder="Select Location Below" id="id_destination_location">
                        </label>
                    </div>
                    <button type="button" id="destination_location_button_take_ride" style="margin-top: -20px;" data-toggle="modal" data-target="#myModal2">Destination</button>
                    <div class="form-row">
                        <button type="submit" class="btn">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <div class="modal fade" id="myModal2">
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
        if (!(!isset($_POST['dateofride']) || trim($_POST['dateofride']) == '')) {
            $fields = array(
                'source_latitiude'=> $_POST['sou_lati'],
                'source_longitude'=> $_POST['sou_long'],
                'destination_latitude'=> $_POST['des_lati'],
                'destination_longitude'=> $_POST['des_long'],
                'ride_date' => $_POST['dateofride']
            );

            require_once("config.php");
            $config = new ConfigVars();

            $have_api_key = 0;

            if (isset($_SESSION['ApiKey'])) {
                $fields['Authorization'] = $_SESSION['ApiKey'];
                $have_api_key = 1;
            }
            else {
                $inner_fields = array (
                    'fb_id' => $_SESSION['oauth_uid']
                );
                $inner_result = $config->send_post_request($inner_fields, "fetchuserdetailsbyfbid");
                $inner_obj = json_decode($inner_result);
                if(!$inner_obj->{'error'}) {
                    $_SESSION['ApiKey'] = $inner_obj->{'apiKey'};
                    $fields['Authorization'] = $_SESSION['ApiKey'];
                    $have_api_key = 1;
                }
            }
            if($have_api_key === 1) {
                $result = $config->send_post_request($fields, "fetchriders");
                echo $result;
                $obj = json_decode($result);
                echo "<script>
                $.notify({
                    message: '" . $obj->{'message'} . "',
                    type: 'success'
                });
                </script>";
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