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
                <input type="hidden" name="sou_lati" id="id_sou_lati" value = "{{ selected_ride.source_latitude }}"/>
            </div>

            <div class="input-group">
                <input type="hidden" name="sou_long" id="id_sou_long" value = "{{ selected_ride.source_longitude }}"/>
            </div>

            <div class="input-group">
                <input type="hidden" name="des_lati" id="id_des_lati" value = "{{ selected_ride.destination_latitude }}"/>
            </div>

            <div class="input-group">
                <input type="hidden" name="des_long" id="id_des_long" value = "{{ selected_ride.destination_longitude }}"/>
            </div>
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Update Ride</h1>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Car Model</span>
                            <input type="text" id="id_car_model" placeholder="Enter Car Model" name="car_model" value = "{{ selected_ride.car_model }}">
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Seats</span>
                            <input type="text" id="id_seats" placeholder="Enter No. of Seats" name="seats" value = "{{ selected_ride.seats }}">
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Seats Available</span>
                            <input type="text" id="id_seats_available" placeholder="Enter No. of Seats Available" name="seats_available" value = "{{ selected_ride.seats_available }}">
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Cost</span>
                            <input type="text" id="id_cost" placeholder="Enter Cost" name="cost" value = "{{ selected_ride.cost }}">
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Start Time</span>
                            <input type="time" id="id_start_time" placeholder="Enter Start Time" name="start_time" value = "{{ selected_ride.start_time }}">
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Date Of Ride</span>
                            <input type="date" id="id_dateofride" name="dateofride" value = "{{ selected_ride.dateofride }}">
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Message</span>
                            <textarea name="message" placeholder="Enter Message" id="id_message">{{ selected_ride.message }}</textarea>
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Source Location</span>
                            <input type="text" name="source_location" maxlength="1000" required="" placeholder="Select Location Below" id="id_source_location" value = "{{ selected_ride.source }}">
                        </label>
                    </div>
                    <button type="button" id="source_location_button" style="margin-top: -20px;" data-toggle="modal" data-target="#myModal">Source</button>
                    <div class="form-row">
                        <label>
                            <span>Destination Location</span>
                            <input type="text" name="destination_location" maxlength="1000" required="" placeholder="Select Location Below" id="id_destination_location" value = "{{ selected_ride.destination }}">
                        </label>
                    </div>
                    <button type="button" id="destination_location_button" style="margin-top: -20px;" data-toggle="modal" data-target="#myModal">Destination</button>
                    <div class="form-row">
                        <button type="submit" class="btn">Update</button>
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
<?php endblock() ?>