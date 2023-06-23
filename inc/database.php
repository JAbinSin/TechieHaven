<?php

    //Variables needed to connect to the database
    $dbhostname = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "techiehaven";

    //Connect to the database
    $connection = new mysqli($dbhostname, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($connection->connect_errno) {
        echo "Failed to connect to MySQL: " . $connection->connect_error;
        exit();
    }

    //Start the session of the webpage
    session_start();

    //Set the name for the Website
    $_SESSION['siteName'] = "TechieHaven ";
?>