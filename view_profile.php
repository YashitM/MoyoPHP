<?php
    session_start();
    $update_profile_button = 0;
    $num_rides = 0;
    require_once("config.php");
    $config = new ConfigVars();
    $inner_fields = array (
        'fb_id' => $_SESSION['oauth_uid']
    );

    $inner_result = $config->send_post_request($inner_fields, "fetchuserdetailsbyfbid");
    $inner_obj = json_decode($inner_result);
    $user_details = $inner_obj;
    if(!$inner_obj->{'error'}) {
        if($inner_obj->{'mobile'} === null || $inner_obj->{'dob'} === null || $inner_obj->{'gender'} === null ) {
            $update_profile_button = 1;
        }
    }

    $inner_result = $config->send_post_request($inner_fields, "fetchinguserpostedrides");
    $inner_obj = json_decode($inner_result);
    if(!$inner_obj->{'error'}) {
        $num_rides = count($inner_obj->{'users'});
    }

    $result = $config->send_post_request($inner_fields, "fetchinguserrides");
    $obj = json_decode($result);
    $ride_taken_count = 0;
    if(!$obj->{'error'}) {
        $ride_ids = array();
        $users = $obj->{'users'};
        for($x = 0; $x<count($users); $x++) {
            if($users[$x]->status === "1") {
                $ride_taken_count += 1;
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
                        <a class="nav-link" href="view_rides.php">Rides Posted</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_taken_rides.php">Rides Requested</a>
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

    <div class="container" style="margin-bottom: 46%;">
        <div class="row login_box">
            <div class="col-md-12 col-xs-12" align="center">
                <div class="line"><h3 class="current_time"><?php echo date("h:i:sa"); ?></h3></div>
                <?php
                if($_SESSION['oauth_provider'] === "Facebook") {
                    echo '<div class="outter"><img src="//graph.facebook.com/'.$_SESSION['oauth_uid'].'/picture?type=large" class="image-circle"/></div>';
                }
                else if($_SESSION['oauth_provider'] === "Google") {
                    echo '<div class="outter"><img src="" class="image-circle"/></div>';
                }

                echo $user_details->gender.", ".$user_details->dob;

                ?>
                <h1 class="profile_name"><?php echo $_SESSION['first_name']." ".$_SESSION['last_name']; ?></h1>
            </div>
            <?php
                if($num_rides > 0) {
                    ?>
                    <a href="view_rides.php" class="col-md-6 col-xs-6 follow line" align="center">
                    <?php
                    }
                    else {
                    ?>
                    <a href="#" class="col-md-6 col-xs-6 follow line" align="center">
                    <?php
                    }
                    ?>
                    <h3>
                        <?php echo $num_rides ?> <br/> <span>Rides Offered</span>
                    </h3>
                </a>
            <a href="" class="col-md-6 col-xs-6 follow line" align="center">
                <h3>
                    <?php echo $ride_taken_count ?> <br/> <span>Rides Taken</span>
                </h3>
            </a>
            <div class="col-md-12 col-xs-12 login_control">
                <div class="control">
                    <div class="label">Registered Mobile</div>
                    <div class="label_text"><?php echo $user_details->mobile; ?></div>
                </div>
                <div class="control">
                    <div class="label">Reference Status</div>
                    <?php
                        if($user_details->ref_status === "0") {
                            echo "<div class=\"label_text\">No References Available</div>";
                        }
                        else {
                            echo "<div class=\"label_text\">Referred to: $user_details->ref_status</div>";
                        }


                    ?>

                </div>

                <div align="center">
                    <?php
                        if($update_profile_button == 1) {
                            echo "<a href=\"update_profile.php\" class=\"btn btn-orange\">Update Profile</a>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
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
                    <?php

                    $have_api_key = 0;

                    if (isset($_SESSION['ApiKey'])) {
                        $fields['Authorization'] = $_SESSION['ApiKey'];
                        $have_api_key = 1;
                    }
                    else {
                        $inner_result = $config->send_post_request($inner_fields, "fetchuserdetailsbyfbid");
                        $inner_obj = json_decode($inner_result);
                        if(!$inner_obj->{'error'}) {
                            $_SESSION['ApiKey'] = $inner_obj->{'apiKey'};
                            $fields['Authorization'] = $_SESSION['ApiKey'];
                            $have_api_key = 1;
                        }
                    }

                    if($have_api_key === 1) {
                    ?>
                    var authorization = "<?php echo $_SESSION['ApiKey']; ?>";
                    $.post("http://carzrideon.com/estRideon/v1/index.php/updateFcmID",
                        {
                            fcm_id: currentToken,
                            Authorization: authorization
                        },
                        function(data, status){
                            console.log("FCM Token Updated in DB");
                        });
                    <?php
                    }
                    else {
                        echo "<script>
                            $.notify({
                                message: 'Some error occurred. Please Refresh The Page',
                                type: 'success'
                            });
                            </script>";
                    }

                    ?>
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
