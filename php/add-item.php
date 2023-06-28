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
        <title><?php echo $_SESSION['siteName']?> | Add Item</title>

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
                $itemName = trim($_POST["itemName"]);
                $itemPrice = trim($_POST["itemPrice"]);
                $itemDescription = trim($_POST["itemDescription"]);
                @$itemCategory = $_POST["itemCategory"];

                // Remove PHP and HTML tags
                $itemName = htmlspecialchars(strip_tags($itemName));
                $itemPrice = htmlspecialchars(strip_tags($itemPrice));
                $itemDescription = htmlspecialchars(strip_tags($itemDescription));
                $itemCategory = htmlspecialchars(strip_tags($itemCategory));

                //Sanitize all the Inputs
                $itemName = filter_var($itemName, FILTER_SANITIZE_SPECIAL_CHARS);
                $itemPrice = filter_var($itemPrice, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $itemCategory = filter_var($itemCategory, FILTER_SANITIZE_SPECIAL_CHARS);

                // For the Error Messages
                $userInputs = array("itemName" => $itemName, "itemPrice" => $itemPrice, "itemDescription" => $itemDescription, "itemCategory" => $itemCategory);

                foreach($userInputs as $k => $v) {
                    if(empty($v))
                        $error[$k] = "Please provide a valid input";
                }

                //Check if the Item Name already exist
                $sqlQuery = "SELECT item_name FROM tbl_items WHERE item_name = '$itemName'";
                $sqlQueryResult = $connection->query($sqlQuery);
                if($sqlQueryResult->num_rows > 0)  
                    $error['itemName'] = "Item Name Already Exist";


                // File Image Validations
                $uploadedImage = false;

				if($_FILES['itemPicture']['error'] == 4){
                    $uploadedImage = false;
				} else {
					//Check if the file type is an image format and if the user upload an image or not
					//Add an exception so it would not check an empty upload
					if((@exif_imagetype($_FILES['itemPicture']['tmp_name']) == false) && (@!empty($_FILES['itemPicture']['tmp_name']))) {
						$error['itemPicture'] = "File Uploaded is not an Image Format / Empty.";
					} else if(@empty(exif_imagetype($_FILES['itemPicture']['tmp_name']))) {
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
                    $queryTableStatus = "SHOW TABLE STATUS LIKE 'tbl_items'";
                    $queryTableStatusResult = $connection->query($queryTableStatus);
                    $tableinfo = $queryTableStatusResult->fetch_assoc();
                    $nextId = $tableinfo['Auto_increment'];

                    //Moving and naming the img to img/category folder
                    if($uploadedImage == true) {
                        $target_dir = "../img/items/";
                        @$fileType = pathinfo($_FILES['itemPicture']['name'])["extension"];
                        $fileName = $nextId . "_picture." . $fileType;
                        $target_file = $target_dir . $fileName;
                        move_uploaded_file($_FILES['itemPicture']['tmp_name'], $target_file);
                    }
                    
                    if($uploadedImage == true){
                        $sqlInsert = "INSERT INTO tbl_items(item_name, item_description, item_price, item_picture, item_category) VALUES ('$itemName', '$itemDescription', '$itemPrice','$fileName', '$itemCategory')";
                        $sqlInsertResult = $connection->query($sqlInsert);
                    } else {
                        $sqlInsert = "INSERT INTO tbl_items(item_name, item_description, item_price, item_category) VALUES ('$itemName', '$itemDescription', '$itemPrice', '$itemCategory')";
					    $sqlInsertResult = $connection->query($sqlInsert);
                    }

                    if($sqlInsertResult) {

                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "<?php echo $itemName; ?> Successfuly Added"</script>
                        <script>myModal.show()</script>
                        <?php 
                    }
                    else {
                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "Error occured. Please try again later. <br><?php echo $connection->error; ?>"</script>
                        <script>myModal.show()</script>
                        <?php 
                    }
                }
            } else {
                // Reset all the inputs on the 1st run of the program
                $itemName = "";
                $itemPrice = "";
                $itemDescription = "";
                $itemCategory = "";
            }
            ?>

        <!-- Container for the input form of the add item -->
        <div class="container p-3 mb-2 bg-normal-92 text-white rounded-3 w-25">
            <h1 class="text-center mb-2">Add Item</h1>
            <!-- This is the form that would need inputs that would be passed to the addItemHandler.php -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="itemPicture" class="form-label">Item Picture</label>
                    <input class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['itemPicture']) ? "is-invalid": "is-valid");?>" type="file" accept="image/*" name="itemPicture">
                    <?php 
						if(isset($error['itemPicture'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['itemPicture'] . "
								</div>
							";
						}
					?>
                </div>
                <div class="mb-3">
                    <label for="itemName" class="form-label">Item Name</label>
                    <input type="text" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['itemName']) ? "is-invalid": "is-valid");?>" name="itemName" placeholder="e.g i5-7400" value="<?php echo $itemName?>" required>
                    <?php 
						if(isset($error['itemName'])) {
							echo "
								<div class='invalid-feedback'>
								" . $error['itemName'] . "
								</div>
							";
						}
					?>
                </div>
                <div class="mb-3">
                    <label for="itemCategory" class="form-label">Item Category</label>
                    <select class='form-select bg-dark text-white mt-1 <?php echo $_SESSION['firstRun'] ? "" : (isset($error['itemCategory']) ? "is-invalid": "is-valid");?>' name='itemCategory' required>
                        <option value="" disabled selected hidden>Please Choose...</option>
                        <?php
                            //Query and Execute for the category
                            $sqlQuery = "SELECT category_name FROM tbl_category WHERE category_name NOT LIKE 'All' ORDER BY category_name";
                            $sqlQueryResult = $connection->query($sqlQuery);
                            while($categoryData = $sqlQueryResult->fetch_assoc()) {
                                $categoryName = $categoryData["category_name"];
                                if($itemCategory == $categoryName) {
                                    echo "
                                        <option value='$categoryName' selected>$categoryName</option>
                                    ";
                                } else {
                                    echo "
                                        <option value='$categoryName'>$categoryName</option>
                                    ";
                                }
                            }
                            if(isset($error['itemCategory'])) {
                                echo "
                                    <div class='invalid-feedback'>
                                    " . $error['itemCategory'] . "
                                    </div>
                                ";
                            }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="itemPrice" class="form-label">Item Price</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">₱</span>
                        <input type="number" class="form-control text-light bg-dark <?php echo $_SESSION['firstRun'] ? "" : (isset($error['itemPrice']) ? "is-invalid": "is-valid");?>" aria-label="Peso amount (with dot and two decimal places)" name="itemPrice" placeholder="e.g 25.00" step=".01" min="1" max="999999999" value="<?php echo $itemPrice?>" required>
                        <?php 
                        if(isset($error['itemPrice'])) {
                            echo "
                                <div class='invalid-feedback'>
                                " . $error['itemPrice'] . "
                                </div>
                            ";
                        }
                        ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="itemDescription" class="form-label">Item Description</label>
                    <textarea class="form-control bg-dark text-light <?php echo $_SESSION['firstRun'] ? "" : (isset($error['itemDescription']) ? "is-invalid": "is-valid");?>" rows="3" name="itemDescription" style="max-height: 15rem;" placeholder="<?php echo $itemDescription?>" required><?php echo $itemDescription?></textarea>
                    <?php 
                        if(isset($error['itemDescription'])) {
                            echo "
                                <div class='invalid-feedback'>
                                " . $error['itemDescription'] . "
                                </div>
                            ";
                        }
                    ?>
                </div>
                <div class="col text-center">
                    <button type="submit" class="btn btn-primary mt-2" name="add">ADD ITEM</button>
                </div>
            </form>
        </div>
    </body>
</html>