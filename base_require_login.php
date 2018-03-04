<?php
    include 'base.php';
    if($_SESSION["logged_in"] === "false") {
        header("Location: index.php");
        exit();
    }
?>


