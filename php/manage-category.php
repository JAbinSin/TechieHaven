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
        <title><?php echo $_SESSION['siteName']?> | Manage Category</title>

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
                $categoryName = $_POST['edit'];
                $categoryId = $_POST['categoryId'];
                ?>
                
                <script>
                    document.getElementById("myModalFormLabelh5").innerHTML = "Edit | <?php echo $categoryName;?>"
                    document.getElementById("myModalFormOutput").innerHTML = "<div class='mb-3'>" +
                                                                                "<label for='categoryPicture' class='form-label'>Category Picture</label>" +
                                                                                "<input class='form-control' type='file' accept='image/*' name='categoryPicture'>" +
                                                                            "</div>" +
                                                                            "<div class='mb-3'>" +
                                                                                "<label for='categoryName' class='form-label'>Category Name (Only Characters and Number Are Allowed)</label>" +
                                                                                "<input type='text' class='form-control ' name='categoryName' placeholder='<?php echo $categoryName?>' pattern='[A-z0-9À-ž\\s]+' value='<?php echo $categoryName?>' required>" +
                                                                                "<input type='hidden' name='categoryId' value='<?php echo $categoryId;?>'>" +
                                                                            "</div>"
                                                                            

                    document.getElementById("myModalFormButtons").insertAdjacentHTML("afterbegin", "<button type='submit' name='edit-action' class='btn btn-primary btn-success'>Update</button>")
                    myModalForm.show()
                </script>

                <?php
            } elseif(isset($_POST['delete'])) {
                include_once("../modals/modal.php");
                $categoryName = $_POST['delete'];
                ?>

                <script>
                    document.getElementById("myModalLabelh5").innerHTML = "Delete | <?php echo $categoryName;?>"
                    document.getElementById("myModalOutput").innerHTML = "Are you Sure you want to delete <?php echo $categoryName;?>?"
                    document.getElementById("myModalButtons").innerHTML = ""
                    document.getElementById("myModalButtons").insertAdjacentHTML("afterbegin", "<form action='' method='post' id='myModalForm'>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='submit' name='delete-action' class='btn btn-danger me-2' value='<?php echo $categoryName;?>'>Yes</button>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>")
                    myModal.show()
                </script>

                <?php
                
            } elseif(isset($_POST['delete-action'])) {
                $categoryName = $_POST['delete-action'];
                
                //Select the category picture
                $queryProfile = "SELECT category_picture FROM tbl_category WHERE category_name = '$categoryName'";
                $queryProfileResult = $connection->query($queryProfile);
                $profileResult = $queryProfileResult->fetch_assoc();
                $path = "../img/category/" . $profileResult['category_picture'];

                //Delete the cateogry picture if the image is not default
                if($profileResult['category_picture'] != "default.png") {
                    unlink($path);
                }

                //Ready the query and execute it to delete the category
                $deleteQuery = "DELETE FROM tbl_category WHERE category_name = '$categoryName'";
                $deleteCategory = $connection->query($deleteQuery);
                
                
            } elseif(isset($_POST['edit-action'])) {
                include_once("../modals/modal.php");

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
                        @$fileType = pathinfo($_FILES['categoryPicture']['name'])['extension'];
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
                        <script>
                            document.getElementById("myModalOutput").innerHTML = "<?php echo $categoryName; ?> Successfuly Updated"
                            myModal.show()
                        </script>
                        <?php 
                    }
                    else {
                        ?>
                        <script>
                            document.getElementById("myModalOutput").innerHTML = "Error occured. Please try again later. <br><?php echo $connection->error; ?>"
                            myModal.show()
                        </script>
                        <?php 
                    }
                } else {
                    if(empty($error['categoryName']))
                        $error['categoryName'] = "";
                    if(empty($error['categoryPicture']))
                        $error['categoryPicture'] = "";
                    ?>
                        <script>
                            document.getElementById("myModalOutput").innerHTML = "<?php echo $error['categoryName']; ?> <br> <?php echo $error['categoryPicture']; ?>"
                            myModal.show()
                        </script>
                    <?php 
                }
            }
        ?>

        <!-- Container for the table of the category list -->
        <div class="container p-3 mb-2 bg-dark text-white table-responsive rounded-3">
            <h1 class="text-center mb-2">Category List</h1>
            <table class="table table-dark table-striped align-middle table-bordered table-responsive">
                <thead class="text-center">
                    <tr>
                        <th>Category Picture</th>
                        <th>Category Name</th>
                        <th>Control</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                        //Query for users in the tbl_users
                        $sqlQuery = "SELECT * FROM tbl_category WHERE category_name NOT LIKE 'All' ORDER BY category_name";
                        $sqlQueryResult = $connection->query($sqlQuery);

                        //This is a loop for table body list
                        while($categoryData = $sqlQueryResult->fetch_assoc()){
                            echo "
                            <tr>
                                <td>
                                    <a href='category-list.php?category={$categoryData['category_name']}'><img src='../img/category/{$categoryData['category_picture']}' alt='Category Unavailable' class='rounded-3' style='width: 5rem; height: 5rem;'></a>
                                </td>
                                <td>
                                    {$categoryData['category_name']}
                                </td>
                                <td class='col-2'>
                                    <form action='' method='post'>
                                        <input type='hidden' name='categoryId' value='{$categoryData['id']}'>
                                        <button type='submit' name='edit' class='btn btn-primary position-relative' value='{$categoryData['category_name']}'>Edit</button>
                                        <button type='submit' name='delete' class='btn btn-danger' value='{$categoryData['category_name']}'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>