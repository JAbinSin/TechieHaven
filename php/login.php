<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");

?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Title of the site  is set in SESSION from the database.php -->
        <title><?php echo $_SESSION['siteName']?> | Login</title>

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
        // This is for the submit validation
		$_SESSION['firstRun'] = true;

		// If the User Click the Register Button
		if(isset($_POST['login'])) {
			$_SESSION['firstRun'] = false;

			// Validate the Inputs
            // Trim the Inputs
			$email = trim($_POST['email']);
			$password = trim($_POST['password']);

            // Remove PHP and HTML tags
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);
			$password = htmlspecialchars(strip_tags($password));
			$password = sha1($password);

			$userInputs = array("email" => $email, "password" => $password);

			foreach($userInputs as $k => $v) {
				if(empty($v)) 
					$error[$k] = "Please provide a valid input";
			}
			
			if(!$email) 
                $error["email"] = "Invalid Email Address";

			$sqlQuery = "SELECT * FROM tbl_users WHERE email = '$email'";
			$sqlQueryResult = $connection->query($sqlQuery);
            $userData = $sqlQueryResult->fetch_assoc();
			if($email == @$userData['email']) {
				if($password == @$userData['password']) {
					$_SESSION['login'] = true;
					$_SESSION['user_id'] = $userData['id'];
					header("Location: ../template.php");
					exit();
				} else {
					$error['email'] = $error['password'] = "Invalid email or password.";
					$_SESSION['login'] = false;
				}
			} else {
				$error['email'] = $error['password'] = "Invalid email or password.";
				$_SESSION['login'] = false;
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
			<h1 class="text-center mb-2 opacity-1">Login</h1>
			<form class="needs-validation" action="" method="post">
				<div class="mb-3">
					<label for="email" class="form-label">Email</label>
					<input type="email" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['email']) ? "is-invalid": "");?>" name="email" placeholder="Enter Email" required>
					<?php 
						if(isset($error['email'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error["email"] . "
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
								" . $error["password"] . "
								</div>
							";
						}
					?>
				</div>
				<div class="col text-center">
					<button type="submit" name="login" class="btn btn-secondary mb-3 rounded-pill shadow-lg">Login</button>
					<p class="m-0">No Account? <a href="signup.php" <?php $_SESSION['firstRun'] = true; ?>>Register</a></p>
				</div>
			</form>
		</div>
    </body>
</html>