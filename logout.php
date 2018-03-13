<?php
if(!isset($_SESSION['logincust'])) {
    header("Location: login.php");
    exit();
}
else if(isset($_GET['logout'])) {
    if($_GET['logout'] === "true") {
        session_unset();
        header("Location: index.php");
        exit();
    }
}
?>
