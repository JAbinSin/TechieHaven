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
        include("../modals/modal.php");
        ?>
        <script>document.getElementById("myModalOutput").innerHTML = "Error occured. Please try again later. <br><?php echo $connection->error; ?>"</script>
        <script>myModal.show()</script>
        <?php
        echo "Failed to connect to MySQL: " . $connection->connect_error;
        exit();
    }

    //Start the session of the webpage
    session_start();

    //Set the name for the Website
    $_SESSION['siteName'] = "TechieHaven ";
?>