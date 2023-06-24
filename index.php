<?php
    //This page would only act as a redirector

    //Connect to the database
    include_once("inc/database.php");

    //Redirect the user to the itemList.php because every user can access this webpage
    header("Location: php/home.php");
?>