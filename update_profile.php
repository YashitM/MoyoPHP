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


    <link rel="stylesheet" type="text/css" href="static/css/style.css">

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif|Merriweather|Raleway" rel="stylesheet">

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


                <li class="nav-item">
                    <a class="nav-link" href="offer_rides.php">Offer Rides</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="take_ride.php">Take Rides</a>
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
                    <form method="post" id="logout_form" action="logout.php">
                        <a class="nav-link" id="logout_submit_link" href="#">Logout</a>
                    </form>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container padded-container">
    <form class="form-login" method="post" action="#">
        {% csrf_token %}
        <div class="form-log-in-with-email">
            <div class="form-white-background">
                <div class="form-title-row">
                    <h1>Update Profile</h1>
                </div>
                {% for field in form %}
                {% if "Fcm" in field.label_tag %}
                <div class="input-group">
                    {{ field.as_hidden }}
                </div>
                {% elif "Gender" in field.label_tag %}
                <div class="form-row">
                    <label>
                        <span>Gender</span>
                        <select name="gender" id="id_gender">
                            <option selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </label>
                </div>
                {% elif "Dob" in field.label_tag %}
                <div class="form-row">
                    <label>
                        <span>{{ field.label_tag }}</span>
                        <input type="date" id="{{ field.id_for_label }}" name="{{ field.name }}" placeholder="Enter {{ field.label }}">
                    </label>
                </div>
                {% else %}
                <div class="form-row">
                    <label>
                        <span>{{ field.label_tag }}</span>
                        <input type="text" id="{{ field.id_for_label }}" name="{{ field.name }}" placeholder="Enter {{ field.label }}">
                    </label>
                </div>
                {% endif %}
                {% endfor %}
                <div class="form-row">
                    <button type="submit" class="btn">Submit</button>
                </div>
            </div>
        </div>
    </form>
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
    navigator.serviceWorker.register("{% static 'website/js/firebase-messaging-sw.js' %}").then(function (registration) {
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