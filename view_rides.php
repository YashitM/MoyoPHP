<?php include 'base.php' ?>

<?php
    startblock('content');
    if(!isset($_SESSION['logincust'])) {
        header("Location: login.php");
        exit();
    }
?>

<?php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ( !(!isset($_POST['sou_lati']) || trim($_POST['sou_lati']) == '') &&
        !(!isset($_POST['dateofride']) || trim($_POST['dateofride']) == '') &&
        !(!isset($_POST['destination_location']) || trim($_POST['destination_location']) == '') &&
        !(!isset($_POST['source_location']) || trim($_POST['source_location']) == '') &&
        !(!isset($_POST['sou_long']) || trim($_POST['sou_long']) == '') &&
        !(!isset($_POST['des_lati']) || trim($_POST['des_lati']) == '') &&
        !(!isset($_POST['des_long']) || trim($_POST['des_long']) == '')) {
        $sou_lati = $_POST['sou_lati'];
        $sou_long = $_POST['sou_long'];
        $des_lati = $_POST['des_lati'];
        $des_long = $_POST['des_long'];
        $dateofride = $_POST['dateofride'];
        $destination_location = $_POST['destination_location'];
        $source_location = $_POST['source_location'];

        require_once("libs/API/DbHandler.php");
        $db = new DbHandler();
        $users = $db->getNearByRiders($_SESSION['oauth_uid'],$sou_lati,$sou_long,$des_lati,$des_long,$dateofride);
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
?>

<?php endblock() ?>