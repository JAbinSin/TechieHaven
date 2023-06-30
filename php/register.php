<?php
	//Include the database to the webpage to access it
	include_once("../inc/database.php");

	//Check if the current user is allowed to access the webpage
	//Only the guest can access this webpage
	if(isset($_SESSION['userType'])) {
		header("Location: ../index.php");
	}
?>

<!doctype html>
<html lang="en">
	<head>
		<!-- Title of the site  is set in SESSION from the database.php -->
		<title><?php echo $_SESSION['siteName']?> | Register</title>

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

		<!-- Link the boostrap5 to the webpage -->
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<script  type="text/javascript" src="../bootstrap/js/bootstrap.bundle.min.js"></script>

		<!-- Link the boostrap icon 1.4 to the webpage -->
		<link rel="stylesheet" href="../bootstrap-icons/bootstrap-icons.css">

		<!-- Link the local css to the webpage -->
		<link href="../css/stylesheet.css" rel="stylesheet">
	</head>

	<body class="d-grid gap-5 bg-secondary">
		<!-- Include the navigation bar to the webpage -->
		<?php include("../inc/navBar.php"); ?>

		<?php
		// This is for the submit validation
		$_SESSION['firstRun'] = true;

		// If the User Click the Register Button
		if(isset($_POST['register'])) {
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


			// Query to find if the Email already exist
			$sqlQuery = "SELECT email FROM tbl_users WHERE email = '$email'";
			$sqlQueryResult = $connection->query($sqlQuery);
			if($sqlQueryResult->num_rows > 0)  
				$error['email'] = "Email Already Exist";
			?> 
			

			<?php
			if(empty($error)) {
				foreach($userInputs as $k => $v) {
					$_SESSION[$k] = $v;
				}

				include("../modals/modal.php");
				

				// INSERT new user
				$sqlInsert = "INSERT INTO tbl_users(first_name, middle_name, last_name, address, city, region, zip_code, password, email, phone_number, user_type)
				VALUES('$fName', '$mName', '$lName', '$address', '$city', '$region', '$zipCode', '$hashedPassword', '$email', '$cellphoneNumber', 'customer')";

				$sqlInsertResult = $connection->query($sqlInsert);

				if($sqlInsertResult) {
					$fName = "";
					$lName = "";
					$mName = "";
					$email = "";
					$password = "";
					$confirmPassword = "";
					$address = "";
					$city = "";
					$region = "";
					$zipCode = "";
					$cellphoneNumber = "";

					$_SESSION['firstRun'] = true;

					?>
					<script>document.getElementById("myModalButtons").insertAdjacentHTML("afterbegin", "<a class='btn btn-primary' href='login.php' role='button'>Login</a>")</script>
					<script>document.getElementById("myModalOutput").innerHTML = "<?php echo $_SESSION['modalEmail']; ?> Successfuly Registered"</script>
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
			$fName = "";
			$lName = "";
			$mName = "";
			$email = "";
			$password = "";
			$confirmPassword = "";
			$address = "";
			$city = "";
			$region = "";
			$zipCode = "";
			$cellphoneNumber = "";
		}
		?>

		<!-- This is the container of the form  -->
		<div class="container p-3 mb-2 bg-normal-92 text-white w-25 rounded-3 mt-5">
			<h1 class="text-center mb-2 opacity-1">Register</h1>
			<form class="needs-validation" action="" method="post">
				<div class="mb-3">
					<label for="fName" class="form-label">First Name</label>
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['fName']) ? "is-invalid": "is-valid");?>" name="fName" placeholder="Enter First Name" pattern="[A-zÀ-ž\s]+" value="<?php echo $fName?>" required>
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
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['mName']) ? "is-invalid": "is-valid");?>" name="mName" placeholder="Enter Middle Name" pattern="[A-zÀ-ž\s]+" value="<?php echo $mName?>" required>
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
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['lName']) ? "is-invalid": "is-valid");?>" name="lName" placeholder="Enter Last Name" pattern="[A-zÀ-ž\s]+" value="<?php echo $lName?>" required>
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
					<input type="email" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['email']) ? "is-invalid": "is-valid");?>" name="email" placeholder="Enter Email" value="<?php echo $email?>" required>
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
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['address']) ? "is-invalid": "is-valid");?>" name="address" placeholder="Enter Address" value="<?php echo $address?>" required>
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
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['city']) ? "is-invalid": "is-valid");?>" name="city" placeholder="Enter City" value="<?php echo $city?>" required>
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
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['region']) ? "is-invalid": "is-valid");?>" name="region" placeholder="Enter Region" value="<?php echo $region?>" required>
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
					<input type="number" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['zipCode']) ? "is-invalid": "is-valid");?>" name="zipCode" placeholder="Enter Zip Code" value="<?php echo $zipCode?>" required>
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
					<input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['cellphoneNumber']) ? "is-invalid": "is-valid");?>" name="cellphoneNumber" placeholder="Enter Cellphone Number" pattern="[0-9]{11}" maxlength="11" minlength="11"value="<?php echo $cellphoneNumber?>" required>
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
					<button type="submit" name="register" class="btn btn-secondary mb-3 rounded-pill shadow-lg">Register</button>
					<p class="m-0">Already have an account? <a href="login.php" <?php $_SESSION['firstRun'] = true; ?>>Login now</a></p>
				</div>
			</form>
		</div>
	</body>
</html>