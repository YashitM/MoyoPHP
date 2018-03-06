<?php include 'base.php' ?>

<?php startblock('content') ?>
<?php
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


    <h1>LOOOOL TESTSSTSTST</h1>
<?php endblock() ?>