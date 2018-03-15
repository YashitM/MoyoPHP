<?php
session_start();
if(!isset($_SESSION['logincust'])) {
    header("Location: login.php");
    exit();
}
else if(isset($_GET['cancel']) && isset($_GET['ride_id'])) {
    if($_GET['cancel'] === "true") {
        $ride_id = $_GET['ride_id'];

        $inner_fields = array (
            'fb_id' => $_SESSION['oauth_uid']
        );
        require_once("config.php");
        $config = new ConfigVars();
        $inner_result = $config->send_post_request($inner_fields, "fetchinguserpostedrides");
        $inner_obj = json_decode($inner_result);
        $found = false;
        if(!$inner_obj->{'error'}) {
            $rides = $inner_obj->{'users'};
            for($x = 0; $x < count($rides); $x++) {
                if($rides[$x]->id == $ride_id) {
                    $found = true;
                    break;
                }

            }
        }
        if($found === true) {
            $fields = array (
                'fb_id' => $_SESSION['oauth_uid'],
                'ride_id' => $ride_id
            );
            $result = $config->send_post_request($fields, "cancelRide");
            $inner_obj = json_decode($result);
            if(!$inner_obj->{'error'}) {
                echo "Lol";
                header("Location: view_rides.php");
                exit();
            }
            else {
                header("Location: view_rides.php");
                exit();
            }
        } else {
            header("Location: view_profile.php");
            exit();
        }

    }
    else {
        echo "LOl";
    }

}
?>
