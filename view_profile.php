<?php include 'base.php' ?>

<?php startblock('content') ?>
<?php
$update_profile_button = 0;
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
            $update_profile_button = 1;
        }
    }
}
?>

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

                echo $_SESSION['first_name']." ".$_SESSION['last_name'];

                ?>
                <!--            {% endif %}-->
                <h1 class="profile_name"><?php echo $_SESSION['first_name']." ".$_SESSION['last_name']; ?></h1>
            </div>
            <a href="view_rides.php" class="col-md-6 col-xs-6 follow line" align="center">
                <h3>
                    {{ num_rides }} <br/> <span>RIDE(s)</span>
                </h3>
            </a>
            <a href="" class="col-md-6 col-xs-6 follow line" align="center">
                <h3>
                    {{ ref_status }} <br/> <span>REF STATUS</span>
                </h3>
            </a>
            <div class="col-md-12 col-xs-12 login_control">
                <div class="control">
                    <div class="label">Email Address</div>
                    <div class="label_text"><?php echo $_SESSION['email']; ?></div>
                </div>
                <div class="control">
                    <div class="label">Username</div>
                    <div class="label_text">{{ user.username }}</div>
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
<?php endblock() ?>