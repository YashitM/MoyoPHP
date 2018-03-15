<?php
session_start();
if(!isset($_SESSION['logincust'])) {
    header("Location: login.php");
    exit();
}
else {
    require_once("config.php");
    $config = new ConfigVars();
    $inner_fields = array (
        'fb_id' => $_SESSION['oauth_uid']
    );
    $inner_result = $config->send_post_request($inner_fields, "fetchuserdetailsbyfbid");
    $inner_obj = json_decode($inner_result);
    if(!$inner_obj->{'error'}) {
        if($inner_obj->{'mobile'} === null || $inner_obj->{'dob'} === null || $inner_obj->{'gender'} === null ) {
            header("Location: update_profile.php");
            exit();
        }
    }
}
?>
    <!DOCTYPE html>
    <html>
    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Website</title>

        <link rel="icon" href="static/images/logo.png">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.4.3/css/mdb.min.css" rel="stylesheet">
        <link href="static/css/bootstrap-social.css" rel="stylesheet">
        <link href="static/css/bootstrap-material-datetimepicker.css" rel="stylesheet">


        <link rel="stylesheet" type="text/css" href="static/css/style.css">

        <!-- FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif|Merriweather|Raleway" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Raleway:400,200' rel='stylesheet' type='text/css'>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTYjDhEfBVeuW7C_zZd5ZzAv86d3ry1CI&libraries=places"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.4.3/js/mdb.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/3.9.0/firebase.js"></script>
    <script src="https://www.gstatic.com/firebasejs/3.9.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/3.9.0/firebase-messaging.js"></script>

    <script src="static/js/logout.js"></script>
    <script src="static/js/bootstrap-notify.min.js"></script>
    <script src="static/js/moment.js"></script>
    <script src="static/js/bootstrap-material-datetimepicker.js"></script>

    <div class="se-pre-con"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="index.php"><img src="static/images/logo.png" class="img-responsive" style="max-height: 45px;">&nbsp;&nbsp;Carz Ride On</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <?php
                    if(isset($_SESSION["logincust"])) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="offer_rides.php">Offer Rides</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="search_ride.php">Take Rides</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view_requests.php">Ride Requests</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view_profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact_us.php">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php?logout=true">Logout</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

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
                            <input type="text" id="id_dateofride" name="dateofride" placeholder="Date of Ride">
                        </label>
                    </div>
                    <script>
                        $('#id_dateofride').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
                    </script>
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
                $date = date('Y-m-d', strtotime($_POST['dateofride']));
                $fields = array(
                    'source_latitiude'=> $_POST['sou_lati'],
                    'source_longitude'=> $_POST['sou_long'],
                    'destination_latitude'=> $_POST['des_lati'],
                    'destination_longitude'=> $_POST['des_long'],
                    'ride_date' => $date
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
                    echo var_dump($fields);
                    $result = $config->send_post_request($fields, "fetchriders");
                    echo $result;
                    $obj = json_decode($result);
                    if(count($obj->{'users'}) == 0) {
                        echo "<script>
                            $.notify({
                                message: 'No Rides Available. Please Check Again Later',
                                type: 'success'
                            });
                            </script>";
                    }
                    else {
                        echo "<script>
                            $.notify({
                                message: '" . var_dump($obj->{'users'}) . "',
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

    <footer id="myFooter" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <br>
                    &nbsp;&nbsp;&nbsp;<img src="static/images/logo.png">
                    <h3 class="logo"><a href="">Carz Ride On</a></h3>
                </div>
                <div class="col-sm-4"></div>
                <div class="col-sm-2">
                    <br>
                    <h5>Support</h5>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms &amp; Conditions</a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <br>
                    <a href="contact_us.php" class="btn btn-primary footer-button" >Contact Us</a>
                    <div class="social-networks">
                        <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                        <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="#" class="google"><i class="fa fa-google-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <p> <span style="font-size: 12px">Powered by</span> <span style="font-weight: 700;">Moyo Solutions</span></p>
        </div>
    </footer>


    <script src="static/js/scrollreveal.js"></script>
    <script src="static/js/loader.js"></script>
    <script src="static/js/maps.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.8.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.8.1/firebase-messaging.js"></script>


    <script>
        firebase.initializeApp({
            messagingSenderId: "659267976289",
            apiKey: "AIzaSyDi7Jsa94Me66Y1kUXKR7ioLAljsq51-GM"
        });

        const messaging = firebase.messaging();
        navigator.serviceWorker.register("static/js/firebase-messaging-sw.js").then(function (registration) {
            messaging.useServiceWorker(registration);
            requestPermission();
            messaging.getToken()
                .then(function(currentToken) {
                    if (currentToken) {
                        var element =  document.getElementById('id_fcm_id');
                        if (typeof(element) !== 'undefined' && element !== null) {
                            element.value = currentToken;
                        }
                        console.log("Token Generated");
                        console.log(currentToken);
                    } else {
                        console.log('No Instance ID token available. Request permission to generate one.');
                    }
                })
                .catch(function(err) {
                    console.log('An error occurred while retrieving token. ', err);
                })
        }).catch(function (err) {
            console.log('ServiceWorker registration failed: ', err);
        });

        messaging.onMessage(function(payload) {
            console.log("Message received. ", payload);
            $.notify({
                message: payload.notification.body,
                type: 'success'
            });
        });

        function requestPermission() {
            messaging.requestPermission()
                .then(function() {
                    console.log('Notification permission granted.');

                })
                .catch(function(err) {
                    console.log('Unable to get permission to notify.', err);
                });
            // [END request_permission]
        }
    </script>

    </body>
    </html>