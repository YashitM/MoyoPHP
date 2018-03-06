<?php include 'base.php' ?>

<?php
startblock('content');
if(!isset($_SESSION['logincust'])) {
    header("Location: login.php");
    exit();
}
?>

<?php endblock() ?>