<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");

    //Check if the current user is allowed to access the webpage
    //Only the admin and buyer can access this webpage
    if(!isset($_SESSION['userType'])) {
        header("Location: ../index.php");
    }

    //Set the id from url or session
    $type = $_SESSION['userType'];
    @$id = $_GET['id'];
    if(empty($id)) {
        $id = $_SESSION['userId'];
    }

    // Query to select the user
    $sqlQuery = "SELECT * FROM tbl_users WHERE id = '$id'";
    $sqlQueryResult = $connection->query($sqlQuery);
    $userData = $sqlQueryResult->fetch_assoc();
    $pId = $userData['id'];
    $pFName = $userData['first_name'];
    $pLName = $userData['last_name'];
    $pMName = $userData['middle_name'];
    $pEmail = $userData['email'];
    $pAddress = $userData['address'];
    $pCity = $userData['city'];
    $pRegion = $userData['region'];
    $pZipCode = $userData['zip_code'];
    $pCellphoneNumber = $userData['phone_number'];
    $pProfilePicture = $userData['profile_picture'];
    $pUserType = $userData['user_type'];

    //Only the admin can access each person profile
    //Each client can only view their own profile
    if(($type != "admin") && ($pId != $_SESSION['userId'])) {
        header("Location: ../index.php");
        exit();
    }

    //Redirect if user didn't exist
    if(empty($pId)) {
        header("Location: ../index.php");
        exit();
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Title of the site  is set in SESSION from the database.php -->
        <title><?php echo $_SESSION['siteName']?> | Profile</title>

        <!-- Bootstrap 5 Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

        <!-- The meta tags used in the webpage -->
        <!-- charset="utf-8" to use almost all the character and symbol in the world -->
        <!-- viewport to make the webpage more responsive -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Link the local css to the webpage -->
        <link href="../css/stylesheet.css" rel="stylesheet">
    </head>

    <body class="d-grid gap-5 bg-secondary">
        <!-- Include the navigation bar to the webpage -->
        <?php include_once("../inc/navBar.php"); ?>

        <!-- Container for the profile information -->
        <div class="container p-3 mb-2 text-white w-75 overflow-auto">
            <div class="row justify-content-center text-break">
                <?php
                    if($_SESSION['userType'] == "admin") {
                        echo "
                            <div class='col-11 text-center border-end-0 bg-dark'>
                                <h1 class='text-end pe-3 pt-1'><a href='#' class='text-reset text-decoration-none' onclick='window.history.go(-1); return false;'><i class='bi bi-arrow-counterclockwise'></i>Back</a></h1>
                                <hr class='m-0'>
                            </div>
                        ";
                    }
                ?>
                <div class="col-4 text-center border-end-0 bg-normal-92">
                    <img src="<?php echo "../img/profile/$pProfilePicture"?>" class="img-fluid rounded-circle mx-auto d-block mt-4 profile-picture border border-5" alt="Picture Unavailable">
                    <p class="m-0 display-6"><?php echo $pFName . " ".  $pMName?></p>
                    <p class="display-6"><?php echo $pLName?></p>
                    <?php
                        if($pUserType == "admin") {
                            echo "
                            <div class='col text-center'>
                                <a class='btn btn-secondary mb-3 rounded-pill shadow-lg disabled' href='profile
                                
                                
                                
                                -edit.php?id=" . sha1($pId) . "' role='button' style='width: 7rem; font-size: 1.1rem;'>Edit</a>
                            </div>
                            ";
                        } elseif($pId != $_SESSION['userId']) {
                            echo "
                            <div class='col text-center'>
                                <a class='btn btn-secondary mb-3 rounded-pill shadow-lg disabled' href='profile-edit.php?id=" . sha1($pId) . "' role='button' style='width: 7rem; font-size: 1.1rem;'>Edit</a>
                            </div>
                            ";
                        } else {
                            echo "
                                <div class='col text-center'>
                                    <a class='btn btn-secondary mb-3 rounded-pill shadow-lg' href='profile-edit.php?id=" . sha1($pId) . "' role='button' style='width: 7rem; font-size: 1.1rem;'>Edit</a>
                                </div>
                            ";
                        }
                    ?>
                </div>
                <div class="col-7 border-end-0 bg-dark">
                    <p class="h3 mt-2">Personal Details:</p>
                    <div>
                        <dl class="row h5">
                            <dt class="col-sm-4 mt-3">Address: </dt>
                            <dd class="col-sm-8 mt-3"><?php echo $pAddress?></dd>
                            <dt class="col-sm-4 mt-3">City: </dt>
                            <dd class="col-sm-8 mt-3"><?php echo $pCity?></dd>
                            <dt class="col-sm-4 mt-3">Region: </dt>
                            <dd class="col-sm-8 mt-3"><?php echo $pRegion?></dd>
                            <dt class="col-sm-4 mt-3">Zip Code: </dt>
                            <dd class="col-sm-8 mt-3"><?php echo $pZipCode?></dd>
                            <dt class="col-sm-4 mt-3">Cellphone: </dt>
                            <dd class="col-sm-8 mt-3"><?php echo $pCellphoneNumber?></dd>
                            <dt class="col-sm-4 mt-3">Email: </dt>
                            <dd class="col-sm-8 mt-3"><?php echo $pEmail?></dd>
                        </dl>
                    </div>
                <div>
            </div>
        </div>
    </body>
</html>