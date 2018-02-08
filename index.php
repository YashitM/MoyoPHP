<?php include 'base.php' ?>

<?php startblock('content') ?>

<header class=" text-white">
    <div class="container text-center" style="color: black; font-family: 'Bree Serif',serif;">
        <h1 style="font-weight: 500;">Welcome to Carz Ride On</h1>
        <p class="lead" style="font-weight: 700;">Intra &amp; Inter-City Car Pooling</p>
    </div>
</header>

<!--<script>-->
<!--    $.notify({-->
<!--        message: '{{ custom_notifications }}',-->
<!--        type: 'success'-->
<!--    });-->
<!--</script>-->

<section id="about" class="bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-6" id="about-left">
                <br>
                <br>
                <h2 style="font-weight: 700;">About Us</h2>
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

<?php endblock() ?>