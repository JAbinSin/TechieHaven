<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");

    //Check if the current session allowed the user to acces this site and redirect if not
    //Only the admin can access this webpage
    if(!($_SESSION['userType'] == "admin")) {
        header("Location: ../index.php");
    }

    //Check if the current session allowed the user to acces this site and redirect if not
    //Need input from the previous form
    if (empty($_POST)) {
        header("location: ../index.php");
        exit();
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Title of the site  is set in SESSION from the database.php -->
        <title><?php echo $_SESSION['siteName']?> | Edit Category</title>

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
            // This is for the edit validation
            $_SESSION['firstRun'] = true;

            if($_SESSION['firstRun']) {
                $category = filter_input(INPUT_POST, 'category');
                $exploded_value = explode('|', $category);
                $categoryId = $exploded_value[0];
                @$categoryName = $exploded_value[1];
            }

            // If the User Click the Register Button
            if(isset($_POST['edit'])) {
                include("../modals/modal.php");
                $_SESSION['firstRun'] = false;

                // Validate the Inputs
                // Trim the Inputs
                $categoryName = trim($_POST['categoryName']);
                $categoryId = $_POST['categoryId'];

                // Remove PHP and HTML tags
                $categoryName = htmlspecialchars(strip_tags($categoryName));

                // For the Error Messages
                $userInputs = array("categoryName" => $categoryName);

                foreach($userInputs as $k => $v) {
                    if(empty($v))
                        $error[$k] = "Please provide a valid input";
                }

                //Check if the Name already exist
                $sqlQuery = "SELECT id, category_name FROM tbl_category";
                $sqlQueryResult = $connection->query($sqlQuery);
                while($categoryData = $sqlQueryResult->fetch_assoc()) {
                    if(($categoryName === $categoryData['category_name']) && ($categoryId != $categoryData['id']))
                        $error['categoryName'] = "Category Name Already Exist";
                }

                // File Image Validations
                $uploadedImage = false;

				if($_FILES['categoryPicture']['error'] == 4){
                    $uploadedImage = false;
				} else {
					//Check if the file type is an image format and if the user upload an image or not
					//Add an exception so it would not check an empty upload
					if((@exif_imagetype($_FILES['categoryPicture']['tmp_name']) == false) && (@!empty($_FILES['categoryPicture']['tmp_name']))) {
						$error['categoryPicture'] = "File Uploaded is not an Image Format / Empty.";
                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "File Uploaded is not an Image Format / Empty."</script>
                        <script>myModal.show()</script>
                        <?php
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

                    //Select the category picture then delete the old category picture
                    $queryImage = "SELECT category_picture FROM tbl_category WHERE id = '$categoryId'";
                    $queryImageResult = $connection->query($queryImage);
                    $imageResult = $queryImageResult->fetch_assoc();
                    $path = "../img/category/" . $imageResult['category_picture'];

                    //Delete the cateogry picture if they change from an image that is not a default
                    //Also stop the user from being able to delete the default profile
                    if(($imageResult['category_picture'] != "default.png") && ($uploadedImage == true)) {
                        unlink($path);
                    }

                    //Moving and naming the img to img/category folder
                    if($uploadedImage == true) {
                        $target_dir = "../img/category/";
                        @$fileType = pathinfo($_FILES['categoryPicture']['name'])["extension"];
                        $fileName = $categoryId . "_picture." . $fileType;
                        $target_file = $target_dir . $fileName;
                        move_uploaded_file($_FILES['categoryPicture']['tmp_name'], $target_file);
                    }
                    
                    if($uploadedImage == true){
                        $sqlUpdate = "UPDATE tbl_category SET category_name = '$categoryName', category_picture = '$fileName' WHERE id = '$categoryId'";
                        $sqlUpdateResult = $connection->query($sqlUpdate);
                    } else {
                        $sqlUpdate = "UPDATE tbl_category SET category_name = '$categoryName' WHERE id = '$categoryId'";
					    $sqlUpdateResult = $connection->query($sqlUpdate);
                    }

                    if($sqlUpdateResult) {

                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "<?php echo $categoryName; ?> Successfuly Updated"</script>
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
            }
        ?>

        <!-- Container for the input form of the add item -->
        <div class="container p-3 mb-2 bg-normal-92 text-white rounded-3 w-25">
            <h1 class="text-center mb-2">Edit Category</h1>
            <!-- This is the form that would need inputs that would be passed to the editCategoryHandler.php -->
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
                    <label for="categoryName" class="form-label">Category Name (Only Characters and Number Are Allowed)</label>
                    <input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['categoryName']) ? "is-invalid": "is-valid");?>" name="categoryName" placeholder="e.g CPU" pattern="[A-z0-9À-ž\s]+" value="<?php echo $categoryName?>" required>
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
                    <input type="hidden" name="categoryId" value="<?php echo "$categoryId";?>">
                    <button type="submit" name="edit" class="btn btn-primary btn-success">EDIT CATEGORY</button>
                </div>
                <div class="col text-center">
                    <a class='btn btn-danger mt-2' href='categorySelector.php?op=edit' role='button'>CANCEL</a>
                </div>
            </form>
        </div>
    </body>
</html>