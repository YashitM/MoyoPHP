<?php include 'base_require_login.php' ?>

<?php startblock('content') ?>

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
                    <h1>Take Ride</h1>
                </div>
                <div class="form-row">
                    <label>
                        <span>Source Location</span>
                        <input type="text" name="source_location" maxlength="1000" required="" placeholder="Select Location Below" id="id_source_location">
                    </label>
                </div>
                <button type="button" id="source_location_button_take_ride" style="margin-top: -20px;" data-toggle="modal" data-target="#myModal2">Source</button>
                <div class="form-row">
                    <label>
                        <span>Destination Location</span>
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

<?php endblock() ?>