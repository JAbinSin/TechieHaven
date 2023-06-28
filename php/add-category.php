<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");

    //Check if the current user is allowed to access the webpage
    //Only the admin can access this webpage
    if($_SESSION['userType'] != "admin") {
        header("Location: ../index.php");
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Title of the site  is set in SESSION from the database.php -->
        <title><?php echo $_SESSION['siteName']?> | Add Category</title>

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
            // This is for the Add validation
            $_SESSION['firstRun'] = true;

            // If the User Click the Register Button
            if(isset($_POST['add'])) {
                include("../modals/modal.php");
                $_SESSION['firstRun'] = false;

                // Validate the Inputs
                // Trim the Inputs
                $categoryName = trim($_POST['categoryName']);

                // Remove PHP and HTML tags
                $categoryName = htmlspecialchars(strip_tags($categoryName));

                // For the Error Messages
                $userInputs = array("categoryName" => $categoryName);

                foreach($userInputs as $k => $v) {
                    if(empty($v))
                        $error[$k] = "Please provide a valid input";
                }

                //Check if the Name already exist
                $sqlQuery = "SELECT category_name FROM tbl_category WHERE category_name = '$categoryName'";
                $sqlQueryResult = $connection->query($sqlQuery);
                if($sqlQueryResult->num_rows > 0)  
                    $error['categoryName'] = "Category Name Already Exist";

                // File Image Validations
                $uploadedImage = false;

				if($_FILES['categoryPicture']['error'] == 4){
                    $uploadedImage = false;
				} else {
					//Check if the file type is an image format and if the user upload an image or not
					//Add an exception so it would not check an empty upload
					if((@exif_imagetype($_FILES['categoryPicture']['tmp_name']) == false) && (@!empty($_FILES['categoryPicture']['tmp_name']))) {
						$error['categoryPicture'] = "File Uploaded is not an Image Format / Empty.";
					} else if(@empty(exif_imagetype($_FILES['categoryPicture']['tmp_name']))) {
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

                    //This query is to select find the id increment value for the image name
                    $queryTableStatus = "SHOW TABLE STATUS LIKE 'tbl_category'";
                    $queryTableStatusResult = $connection->query($queryTableStatus);
                    $tableinfo = $queryTableStatusResult->fetch_assoc();
                    $nextId = $tableinfo['Auto_increment'];

                    //Moving and naming the img to img/category folder
                    if($uploadedImage == true) {
                        $target_dir = "../img/category/";
                        @$fileType = pathinfo($_FILES['categoryPicture']['name'])["extension"];
                        $fileName = $nextId . "_picture." . $fileType;
                        $target_file = $target_dir . $fileName;
                        move_uploaded_file($_FILES['categoryPicture']['tmp_name'], $target_file);
                    }
                    
                    if($uploadedImage == true){
                        $sqlInsert = "INSERT INTO tbl_category(category_name, category_picture) VALUES ('$categoryName', '$fileName')";
                        $sqlInsertResult = $connection->query($sqlInsert);
                    } else {
                        $sqlInsert = "INSERT INTO tbl_category(category_name) VALUES ('$categoryName')";
					    $sqlInsertResult = $connection->query($sqlInsert);
                    }

                    if($sqlInsertResult) {

                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "<?php echo $categoryName; ?> Successfuly Added"</script>
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
                $categoryName = "";
            }
            ?>

        <!-- Container for the input form of the add category -->
        <div class="container p-3 mb-2 bg-normal-92 text-white rounded-3 w-25">
            <h1 class="text-center mb-2">Add Category</h1>
            <!-- This is the form that would need inputs that would be passed to the addCategoryHandler.php -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="categoryPicture" class="form-label">Category Picture</label>
                    <input class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['categoryPicture']) ? "is-invalid": "is-valid");?>" type="file" accept="image/*" name="categoryPicture">
                    <?php 
						if(isset($error['categoryPicture'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['categoryPicture'] . "
								</div>
							";
						}
					?>
                </div>
                <div class="mb-3">
                    <label for="categoryName" class="form-label">Category Name</label>
                    <input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['categoryName']) ? "is-invalid": "is-valid");?>" name="categoryName" placeholder="e.g CPU" pattern="[A-z0-9À-ž\s]+" value="<?php echo $categoryName?>" required>
                    <div class="form-text text-light">(Only Characters and Number Are Allowed)</div>
                    <?php 
						if(isset($error['categoryName'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['categoryName'] . "
								</div>
							";
						}
					?>
                </div>
                <div class="col text-center">
                    <button type="submit" name="add" class="btn btn-primary btn-success">ADD CATEGORY</button>
                </div>
            </form>
        </div>
    </body>
</html>