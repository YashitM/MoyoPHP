<?php
    session_start();
//    include_once 'loginG.php';
//    if(isset($_GET['code'])){
//        $gClient->authenticate($_GET['code']);
//        $_SESSION['token'] = $gClient->getAccessToken();
//        header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
//    }
//    if (isset($_SESSION['token'])) {
//        $gClient->setAccessToken($_SESSION['token']);
//    }
//    if ($gClient->getAccessToken())
//    {
//        $gpUserProfile = $google_oauthV2->userinfo->get();
//        $_SESSION['oauth_provider'] = 'Google';
//        $_SESSION['oauth_uid'] = $gpUserProfile['id'];
//        $_SESSION['first_name'] = $gpUserProfile['given_name'];
//        $_SESSION['last_name'] = $gpUserProfile['family_name'];
//        $_SESSION['email'] = $gpUserProfile['email'];
//        $_SESSION['logincust']='yes';
//    }
    if(isset($_SESSION['notification_message'])) {
        echo "<script>
        $.notify({
            message: '".$_SESSION['notification_message']."',
            type: 'success'
        });
        </script>";
        unset($_SESSION['notification_message']);
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

    <header class=" text-white">
        <div class="container text-center" style="color: black; font-family: 'Bree Serif',serif;">
            <h1 style="font-weight: 500;">Welcome to Carz Ride On</h1>
            <p class="lead" style="font-weight: 700;">Intra &amp; Inter-City Car Pooling</p>
        </div>
    </header>

    <section id="about" class="bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-6" id="about-left">
                    <br>
                    <br>
                    <h2 style="font-weight: 700;">About Us</h2>
                    <?php echo $_SESSION['age']; ?>
                    <p class="lead sub-heading">Are you travelling alone? Do you want to TAKE or OFFER Ride?</p>
                    <p>Carz Ride On App simplifies your travel. You can offer ride and can take ride so that you can save your money and fuel for an Eco Friendly Environment! We provide Intra and Inter-city rides. Offer or Take rides from your source to destination of your journey.</p>
                </div>
                <div class="col-lg-6" id="about-right">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/K6F7wwcF-iM" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <h2 style="font-weight: 700;">Services We Offer</h2>
                    <br>
                    <div class="sub-heading-div">
                        <p class="lead sub-heading">Take Ride</p>
                        <p>Select your starting point from where you would like to travel and the destination point on which date is your journey. Select the most relevant rides.</p>
                    </div>
                    <div class="sub-heading-div">
                        <p class="lead sub-heading">Offer Ride</p>
                        <p>Enter your source and destination of your journey.Specifying date,time,car-model,number of seats and cost per seat. With a personalized message.With this you have successfully offer a ride.</p>
                    </div>
                    <div class="sub-heading-div">
                        <p class="lead sub-heading">References</p>
                        <p>At the time of registration you have to provide mobile number of your reference. This has to be approved by the reference which you have provided. This makes a rider or traveller more trust worthy.</p>
                    </div>
                    <div class="sub-heading-div">
                        <p class="lead sub-heading">Contact Us</p>
                        <p>If you like to give feedback/query/complaint you can always submit along with the screenshots if you have any.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
