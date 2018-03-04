<?php include 'base.php' ?>

<?php startblock('content') ?>
<?php
if(!isset($_SESSION['logincust'])) {
    header("Location: login.php");
    exit();
}
?>


    <h1>LOOOOL TESTSSTSTST</h1>
<?php endblock() ?>