<?php
  //This destroy the session and reset it so the user can login again
  include_once("../inc/database.php");
  session_destroy();
  header("Location: login.php");
?>