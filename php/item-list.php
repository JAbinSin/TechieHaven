<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");

    //Check if the current session allowed the user to acces this site and redirect if not
    if(empty($_GET['category'])) {
        header("Location: ../index.php");
    }

    //Get the id from the url
    $categoryName = $_GET['category'];

    //Error handling
    $itemEmpty = true;
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Title of the site  is set in SESSION from the database.php -->
        <title><?php echo $_SESSION['siteName']?> | <?php echo $categoryName?></title>

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
        <?php include("../inc/navBar.php"); ?>

        <?php
            // If the User Click the Edit Button
            if(isset($_POST['edit'])) {
                include_once("../modals/modalForm.php");
                $itemName = $_POST['edit'];
                $itemId = $_POST['itemId'];
                $itemCategory = $_POST['itemCategory'];
                $itemPrice = $_POST['itemPrice'];
                $itemDescription = $_POST['itemDescription'];

                // Remove Price Formatting
                $itemPrice = str_replace(",", "", $itemPrice);;

                //Query and Execute for the category
                $sqlQuery = "SELECT category_name FROM tbl_category WHERE category_name NOT LIKE 'All' ORDER BY category_name";
                $sqlQueryResult = $connection->query($sqlQuery);
                ?>
                
                <script>
                    document.getElementById("myModalFormLabelh5").innerHTML = "Edit | <?php echo $itemName;?>"
                    document.getElementById("myModalFormOutput").innerHTML = "<div class='mb-3'>" +
                                                                                "<label for='itemPicture' class='form-label'>Item Picture</label>" +
                                                                                "<input class='form-control' type='file' accept='image/*' name='itemPicture'>" +
                                                                            "</div>" +
                                                                            "<div class='mb-3'>" +
                                                                                "<label for='itemName' class='form-label'>Item Name</label>" +
                                                                                "<input type='text' class='form-control ' name='itemName' placeholder='<?php echo $itemName?>' value='<?php echo $itemName?>' required>" +
                                                                                "<input type='hidden' name='itemId' value='<?php echo $itemId;?>'>" +
                                                                            "</div>" +
                                                                            "<div class='mb-3'>" +
                                                                                "<label for='itemCategory' class='form-label'>Item Category</label>" +
                                                                                "<select class='form-select mt-1' name='itemCategory' required>" +
                                                                                    "<option value='' disabled selected hidden>Please Choose...</option>" +
                                                                                    <?php
                                                                                        while($categoryData = $sqlQueryResult->fetch_assoc()) {
                                                                                            $categoryName = $categoryData["category_name"];
                                                                                            if($itemCategory == $categoryName) {
                                                                                                echo "\"<option value='$categoryName' selected>$categoryName</option>\"+";
                                                                                            } else {
                                                                                                echo "\"<option value='$categoryName'>$categoryName</option>\"+";
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                "</select>" +
                                                                            "</div>" +
                                                                            "<div class='mb-3'>" +
                                                                                "<label for='itemPrice' class='form-label'>Item Price</label>" +
                                                                                "<div class='input-group mb-3'>" +
                                                                                    "<span class='input-group-text'>₱</span>" +
                                                                                    "<input type='number' class='form-control' aria-label='Peso amount (with dot and two decimal places)' name='itemPrice' placeholder='e.g 25.00' step='.01' min='1' max='999999999' value='<?php echo $itemPrice?>' required>" +
                                                                                "</div>" +
                                                                            "</div>" +
                                                                            "<div class='mb-3'>" +
                                                                                "<label for='itemDescription' class='form-label'>Item Description</label>" +
                                                                                "<textarea class='form-control' rows='3' name='itemDescription' style='max-height: 15rem;' placeholder='<?php echo $itemDescription?>' required><?php echo $itemDescription?></textarea>" +
                                                                            "</div>"

                    document.getElementById("myModalFormButtons").insertAdjacentHTML("afterbegin", "<button type='submit' name='edit-action' class='btn btn-primary btn-success'>Update</button>")
                    myModalForm.show()
                </script>

                <?php
            } elseif(isset($_POST['delete'])) {
                include_once("../modals/modal.php");
                $itemName = $_POST['delete'];
                $itemId = $_POST['itemId'];
                ?>

                <script>
                    document.getElementById("myModalLabelh5").innerHTML = "Delete | <?php echo $itemName;?>"
                    document.getElementById("myModalOutput").innerHTML = "Are you Sure you want to delete <?php echo $itemName;?>?"
                    document.getElementById("myModalButtons").innerHTML = ""
                    document.getElementById("myModalButtons").insertAdjacentHTML("afterbegin", "<form action='' method='post' id='myModalForm'>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='submit' name='delete-action' class='btn btn-danger me-2' value='<?php echo $itemId;?>'>Yes</button>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>")
                    myModal.show()
                </script>

                <?php
                
            } elseif(isset($_POST['delete-action'])) {
                $itemId = $_POST['delete-action'];
                
                //Ready the query and execute it to delete the category
                $deleteQuery = "DELETE FROM tbl_items WHERE id = '$itemId'";
                $deleteCategory = $connection->query($deleteQuery);
                
                
            } elseif(isset($_POST['edit-action'])) {
                include_once("../modals/modal.php");

                $itemId = $_POST['itemId'];

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

                //Check if the Name already exist
                $sqlQuery = "SELECT id, item_name FROM tbl_items";
                $sqlQueryResult = $connection->query($sqlQuery);
                while($itemData = $sqlQueryResult->fetch_assoc()) {
                    if(($itemName === $itemData['item_name']) && ($itemId != $itemData['id']))
                        $error['itemName'] = "Item Name Already Exist";
                }

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

                    //Select the item picture then delete the old item picture
                    $queryImage = "SELECT item_picture FROM tbl_items WHERE id = '$itemId'";
                    $queryImageResult = $connection->query($queryImage);
                    $imageResult = $queryImageResult->fetch_assoc();
                    $path = "../img/items/" . $imageResult['item_picture'];

                    //Delete the item picture if they change from an image that is not a default
                    //Also stop the user from being able to delete the default profile
                    if(($imageResult['item_picture'] != "default.png") && ($uploadedImage == true)) {
                        unlink($path);
                    }

                    //Moving and naming the img to img/category folder
                    if($uploadedImage == true) {
                        $target_dir = "../img/items/";
                        @$fileType = pathinfo($_FILES['itemPicture']['name'])["extension"];
                        $fileName = $itemId . "_picture." . $fileType;
                        $target_file = $target_dir . $fileName;
                        move_uploaded_file($_FILES['itemPicture']['tmp_name'], $target_file);
                    }
                    
                    if($uploadedImage == true){
                        $sqlUpdate = "UPDATE tbl_items SET item_name = '$itemName', item_description = '$itemDescription', item_price = '$itemPrice', item_picture = '$fileName', item_category = '$itemCategory' WHERE id = '$itemId'";
                        $sqlUpdateResult = $connection->query($sqlUpdate);
                    } else {
                        $sqlUpdate = "UPDATE tbl_items SET item_name = '$itemName', item_description = '$itemDescription', item_price = '$itemPrice', item_category = '$itemCategory' WHERE id = '$itemId'";
					    $sqlUpdateResult = $connection->query($sqlUpdate);
                    }

                    if($sqlUpdateResult) {
                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "<?php echo $itemName; ?> Successfuly Updated"</script>
                        <script>myModal.show()</script>
                        <?php 
                    }
                    else {
                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "Error occured. Please try again later. <br><?php echo $connection->error; ?>"</script>
                        <script>myModal.show()</script>
                        <?php 
                    }
                } else {
                    if(empty($error['itemName']))
                        $error['itemName'] = "";
                    if(empty($error['itemDescription']))
                        $error['itemDescription'] = "";
                    if(empty($error['itemPrice']))
                        $error['itemPrice'] = "";
                    if(empty($error['itemPicture']))
                        $error['itemPicture'] = "";
                    if(empty($error['itemCategory']))
                        $error['itemCategory'] = "";
                    ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "<?php echo $error['itemName']; ?> <br> <?php echo $error['itemDescription']; ?> <br> <?php echo $error['itemPrice']; ?><br> <?php echo $error['itemPicture']; ?><br> <?php echo $error['itemCategory']; ?>"</script>
                        <script>myModal.show()</script>
                    <?php 
                }
            }
        ?>

        <!-- Container for the whole list of items -->
        <div class="container p-3 mb-2 bg-normal-92 text-white rounded-3">
            <div class="row g-0">
                <div class="col-sm-6 col-md-8 ps-3">
                    <h1><?php echo $categoryName?></h1>
                </div>
                <div class="col-6 col-md-4 text-end pe-3">
                    <h1><a href="categoryList.php" class="text-white text-decoration-none" onclick="window.history.go(-1); return false;"><i class="bi bi-arrow-counterclockwise"></i>Back</a></h1>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-4 g-4 row justify-content-md-center">
                <?php
                    //Query and Execute for the item information
                    if($categoryName == "All") {
                        $sqlQuery = "SELECT * FROM tbl_items ORDER BY item_name";
                    } else {
                        $sqlQuery = "SELECT * FROM tbl_items WHERE item_category = '$categoryName' ORDER BY item_name";
                    }
                    $sqlQueryResult = $connection->query($sqlQuery);

                    while($itemData = $sqlQueryResult->fetch_assoc()){
                        $itemEmpty = false;
                        $itemId = $itemData['id'];
                        $itemName = $itemData['item_name'];
                        $itemPrice = $itemData['item_price'];
                        $itemPicture = $itemData['item_picture'];
                        $itemCategory = $itemData['item_category'];
                        $itemDescription = $itemData['item_description'];

                        //Make variable to Number Format
                        $itemPrice = number_format($itemPrice, 2, '.', ',');

                        if(@$_SESSION['userType'] == "admin") {
                            echo"
                                <div class='col text-center itemList-card-admin'>
                                    <div class='card h-100 border border-secondary border-3 card-color'>
                                            <a href='item.php?id=$itemId'><img src='../img/items/$itemPicture' class='card-img-top m-2 rounded-3 itemList-card-image-admin' alt='Image Unavailable'></a>
                                        <div class='card-body text-break'>
                                            <h5 class='card-title module line-clamp p-1'><a href='item.php?id=$itemId' class='text-white text-decoration-none'>$itemName</a></h5>
                                        </div>
                                        <div class='card-footer text-white'>
                                            <strong>₱$itemPrice</strong>
                                        </div>
                                        <div class='card-footer text-white'>
                                            <form action='' method='post'>
                                                <input type='hidden' name='itemId' value='{$itemId}'>
                                                <input type='hidden' name='itemCategory' value='{$itemCategory}'>
                                                <input type='hidden' name='itemPrice' value='{$itemPrice}'>
                                                <input type='hidden' name='itemDescription' value='{$itemDescription}'>
                                                <button type='submit' name='edit' class='btn btn-primary' value='{$itemName}'>Edit</button> |
                                                <button type='submit' name='delete' class='btn btn-danger' value='{$itemName}'>Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            ";
                        } else {
                            echo"
                                <div class='col text-center itemList-card-client'>
                                    <div class='card h-100 border border-secondary border-3 card-color'>
                                            <a href='item.php?id=$itemId'><img src='../img/items/$itemPicture' class='card-img-top m-2 rounded-3 itemList-card-image-client' alt='Image Unavailable'></a>
                                        <div class='card-body text-break'>
                                            <h5 class='card-title module line-clamp p-1'><a href='item.php?id=$itemId' class='text-white text-decoration-none'>$itemName</a></h5>
                                        </div>
                                        <div class='card-footer text-white'>
                                            <strong>₱$itemPrice</strong>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                    }

                    if($itemEmpty) {
                        echo "
                          <div class='alert alert-warning text-center w-100' role='alert'>
                              <h2>No Available Item Yet.</h2>
                          </div>";
                    }
                ?>
            </div>
        </div>
    </body>
</html>