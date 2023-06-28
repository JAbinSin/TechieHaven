<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");

    //Check if the current session allowed the user to acces this site and redirect if not
    //Only the buyer can access this webpage
    if(!($_SESSION['userType'] == "buyer")) {
        header("Location: ../index.php");
    }

    //Redirect the user if the id is invalid
    if(!$_GET['id'] == sha1($_SESSION['userId'])) {
        header("Location: ../index.php");
    }

    // Query to select the user
    $sqlQuery = "SELECT * FROM tbl_users WHERE id = ". $_SESSION['userId'];
    $sqlQueryResult = $connection->query($sqlQuery);
    $userData = $sqlQueryResult->fetch_assoc();
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Title of the site  is set in SESSION from the database.php -->
        <title><?php echo $_SESSION['siteName']?> | Profile Edit</title>

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

        <?php
            // This is for the Update validation
            $_SESSION['firstRun'] = true;

            // If the User Click the Register Button
            if(isset($_POST['update'])) {
                include("../modals/modal.php");
                $_SESSION['firstRun'] = false;

                // Validate the Inputs
                // Trim the Inputs
                $fName = trim($_POST['fName']);
                $mName = trim($_POST['mName']);
                $lName = trim($_POST['lName']);
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);
                $confirmPassword = trim($_POST['confirmPassword']);
                $address = trim($_POST['address']);
                $city = trim($_POST['city']);
                $region = trim($_POST['region']);
                $zipCode = trim($_POST['zipCode']);
                $cellphoneNumber = trim($_POST['cellphoneNumber']);

                // Remove PHP and HTML tags
                $fName = htmlspecialchars(strip_tags($fName));
                $mName = htmlspecialchars(strip_tags($mName));
                $lName = htmlspecialchars(strip_tags($lName));
                $email = filter_var($email, FILTER_VALIDATE_EMAIL);
                $password = htmlspecialchars(strip_tags($password));
                $confirmPassword = htmlspecialchars(strip_tags($confirmPassword));
                $address = htmlspecialchars(strip_tags($address));
                $city = htmlspecialchars(strip_tags($city));
                $region = htmlspecialchars(strip_tags($region));
                $zipCode =  filter_var($zipCode, FILTER_SANITIZE_NUMBER_INT);
                $cellphoneNumber = filter_var($cellphoneNumber, FILTER_SANITIZE_NUMBER_INT);
                $hashedPassword = sha1($password);

                $_SESSION['modalEmail'] = $email;
                $id = $_SESSION['userId'];

                // For the Error Messages
                $userInputs = array("fName" => $fName, "mName" => $mName, "lName" => $lName, "email" => $email, "password" => $password, "confirmPassword" => $confirmPassword, "address" => $address, "city" => $city, "region" => $region, "zipCode" => $zipCode, "cellphoneNumber" => $cellphoneNumber);

                if(!($password === $confirmPassword))
                    $error['password'] = $error['confirmPassword'] = "Does not Match";

                foreach($userInputs as $k => $v) {
                    if(empty($v))
                        $error[$k] = "Please provide a valid input";
                }
                
                if(!$email) 
                    $error['email'] = "Invalid Email Address";


                //Check if the Email, and Phone Number already exist
                $sqlQuery = "SELECT id, email, phone_number FROM tbl_users";
                $sqlQueryResult = $connection->query($sqlQuery);

                while($userData = $sqlQueryResult->fetch_assoc()) {
                    if(($email === $userData['email']) && ($id != $userData['id'])) {
                        $error['email'] = "Email Address Already Registered";
                    }
                    if(($cellphoneNumber === $userData['phone_number']) && ($id != $userData['id'])) {
                        $error['cellphone_number'] = "Cellphone Number Already Registered";
                    }
                }

                // File Image Validations
                $uploadedImage = false;

				if($_FILES['profilePicture']['error'] == 4){
                    $uploadedImage = false;
				} else {
					//Check if the file type is an image format and if the user upload an image or not
					//Add an exception so it would not check an empty upload
					if((@exif_imagetype($_FILES['profilePicture']['tmp_name']) == false) && (@!empty($_FILES['profilePicture']['tmp_name']))) {
                        $error['profilePicture'] = "File Uploaded is not an Image Format / Empty.";
					} elseif(@empty(exif_imagetype($_FILES['profilePicture']['tmp_name']))) {
						$uploadedImage = false;
					} else {
						$uploadedImage = true;
					}
				}

                
                // Update if no Error found
                if(empty($error)) {
                    foreach($userInputs as $k => $v) {
                        $_SESSION[$k] = $v;
                    }
                    
                    //Select the profile picture then delete the old profile picture
                    $queryProfile = "SELECT profile_picture FROM tbl_users WHERE id = '$id'";
                    $queryProfileResult = $connection->query($queryProfile);
                    $profileResult = $queryProfileResult->fetch_assoc();
                    $path = "../img/profile/" . $profileResult['profile_picture'];

                    //Delete the profile picture if they change from an image that is not a default
                    //Also stop the user from being able to delete the default profile
                    if(($profileResult['profile_picture'] != "default.png") && ($uploadedImage == true)) {
                        unlink($path);
                    }

                    //Moving and naming the img to img/profile folder
                    if($uploadedImage == true) {
                        $target_dir = "../img/profile/";
                        @$fileType = pathinfo($_FILES['profilePicture']['name'])['extension'];
                        $fileName = $id . "_profile." . $fileType;
                        $target_file = $target_dir . $fileName;
                        move_uploaded_file($_FILES['profilePicture']['tmp_name'], $target_file);
                    }
                    
                    if($uploadedImage == true){
                        $sqlUpdate = "UPDATE tbl_users SET first_name = '$fName', middle_name = '$mName', last_name = '$lName', address = '$address', city = '$city', region = '$region', zip_code = '$zipCode', password = '$hashedPassword', email = '$email', phone_number = '$cellphoneNumber', profile_picture = '$fileName' WHERE id = '$id'";
					    $sqlUpdateResult = $connection->query($sqlUpdate);
                    } else {
                        $sqlUpdate = "UPDATE tbl_users SET first_name = '$fName', middle_name = '$mName', last_name = '$lName', address = '$address', city = '$city', region = '$region', zip_code = '$zipCode', password = '$hashedPassword', email = '$email', phone_number = '$cellphoneNumber' WHERE id = '$id'";
					    $sqlUpdateResult = $connection->query($sqlUpdate);
                    }

                    if($sqlUpdateResult) {

                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "<?php echo $_SESSION['modalEmail']; ?> Successfuly Updated"</script>
                        <script>document.getElementById("myModalButtons").insertAdjacentHTML("afterbegin", "<a class='btn btn-primary' href='profile.php' role='button'>Profile</a>")</script>
                        <script>myModal.show()</script>

                        <?php 
                    }
                    else {
                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "Error occured. Please try again later. <br><?php echo $connection->error; ?>"</script>
                        <script>myModal.show()</script>
                        <?php 
                    }
                    $connection->close();
                }
            } else {
                // Reset all the inputs on the 1st run of the program
                $id = $userData['id'];
                $fName = $userData['first_name'];
                $lName = $userData['last_name'];
                $mName = $userData['middle_name'];
                $email = $userData['email'];
                $address = $userData['address'];
                $city = $userData['city'];
                $region = $userData['region'];
                $zipCode = $userData['zip_code'];
                $cellphoneNumber = $userData['phone_number'];
            }
            ?>

        <!-- Container for the profile edit -->
        <div class="container p-3 mb-2 bg-normal-92 text-white w-25 rounded-3">
            <form action="" method="post" enctype="multipart/form-data">
                <h1 class="text-center mb-2">Update Profile Information</h1>
                <div class="mb-3">
                    <label for="profilePicture" class="form-label">Profile Picture</label>
                    <input class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['profilePicture']) ? "is-invalid": "is-valid");?>" type="file" accept="image/*" name="profilePicture">
                    <?php 
						if(isset($error['profilePicture'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['profilePicture'] . "
								</div>
							";
						}
					?>
                </div>
                <div class="mb-3">
					<label for="fName" class="form-label">First Name</label>
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['fName']) ? "is-invalid": "is-valid");?>" name="fName" placeholder="<?php echo $fName?>" pattern="[A-zÀ-ž\s]+" value="<?php echo $fName?>" required>
					<?php 
						if(isset($error['fName'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['fName'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="mName" class="form-label">Middle Name</label>
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['mName']) ? "is-invalid": "is-valid");?>" name="mName" placeholder="<?php echo $mName?>" pattern="[A-zÀ-ž\s]+" value="<?php echo $mName?>" required>
					<?php 
						if(isset($error['mName'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['mName'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="mName" class="form-label">Last Name</label>
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['lName']) ? "is-invalid": "is-valid");?>" name="lName" placeholder="<?php echo $lName?>" pattern="[A-zÀ-ž\s]+" value="<?php echo $lName?>" required>
					<?php 
						if(isset($error['lName'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['lName'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="email" class="form-label">Email</label>
					<input type="email" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['email']) ? "is-invalid": "is-valid");?>" name="email" placeholder="<?php echo $email?>" value="<?php echo $email?>" required>
					<?php 
						if(isset($error['email'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['email'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="password" class="form-label">Password</label>
					<input type="password" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['password']) ? "is-invalid": "");?>" name="password" placeholder="Enter Password" required>
					<?php 
						if(isset($error['password'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['password'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="confirmPassword" class="form-label">Confirm Password</label>
					<input type="password" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['confirmPassword']) ? "is-invalid": "");?>" name="confirmPassword" placeholder="Confirm Password" required>
					<?php 
						if(isset($error['confirmPassword'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['confirmPassword'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="address" class="form-label">Address</label>
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['address']) ? "is-invalid": "is-valid");?>" name="address" placeholder="<?php echo $address?>" value="<?php echo $address?>" required>
					<?php 
						if(isset($error['address'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['address'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="city" class="form-label">City</label>
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['city']) ? "is-invalid": "is-valid");?>" name="city" placeholder="<?php echo $city?>" value="<?php echo $city?>" required>
					<?php 
						if(isset($error['city'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['city'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="region" class="form-label">Region</label>
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['region']) ? "is-invalid": "is-valid");?>" name="region" placeholder="<?php echo $region?>" value="<?php echo $region?>" required>
					<?php 
						if(isset($error['region'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['region'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="zipCode" class="form-label">Zip Code</label>
					<input type="number" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['zipCode']) ? "is-invalid": "is-valid");?>" name="zipCode" placeholder="<?php echo $zipCode?>" value="<?php echo $zipCode?>" required>
					<?php 
						if(isset($error['zipCode'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['zipCode'] . "
								</div>
							";
						}
					?>
				</div>
				<div class="mb-3">
					<label for="cellphoneNumber" class="form-label">Cellphone Number (11-Digits)</label>
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['cellphoneNumber']) ? "is-invalid": "is-valid");?>" name="cellphoneNumber" placeholder="<?php echo $cellphoneNumber?>" pattern="[0-9]{11}" maxlength="11" minlength="11"value="<?php echo $cellphoneNumber?>" required>
					<?php 
						if(isset($error['cellphoneNumber'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['cellphoneNumber'] . "
								</div>
							";
						}
					?>
				</div>
                <div class="col text-center">
                    <button type="submit" name="update" class="btn btn-primary btn-success">UPDATE</button>
                    <br>
                    <a class='btn btn-secondary mt-2' href='profile.php' role='button'>CANCEL</a>
                </div>
            </form>
        </div>
    </body>
</html>