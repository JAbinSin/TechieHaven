<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");

    //Check if the current session allowed the user to acces this site and redirect if not
    //Only the admin can access this webpage
    if(!($_SESSION['userType'] == "admin")) {
        header("Location: ../index.php");
    }

    //Check if the current session allowed the user to acces this site and redirect if not
    if(empty($_GET["op"])) {
        header("Location: ../index.php");
    }

    //Get the id from the url
    $operation = $_GET["op"];

    // Check if the operation is valid
    //Check if the current session allowed the user to acces this site and redirect if not
    if(!($operation == "edit" || $operation == "delete")) {
        header("Location: ../index.php");
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

        <!-- Container for the input form of the add item -->
        <div class="container p-3 mb-2 bg-normal-92 text-white rounded-3 w-25">
            <?php
                //This is the form for the edit operation
                if($operation == "edit") {
                    echo "
                        <h1 class='text-center mb-2'>Edit Category</h1>
                        <form action='edit-category.php' method='post'>
                    ";
                } else {
                    //This is the form for the delete operation
                    echo "
                        <h1 class='text-center mb-2'>Delete Category</h1>
                        <form action='delete-category.php' method='post'>
                    ";
                }
            ?>
                <div class="mb-3">
                    <label for="category" class="form-label">Category Name</label>
                    <select class='form-select bg-dark text-white mt-1' name="category" required>
                        <option value="" disabled selected hidden>Please Choose...</option>
                        <?php
                            //Query and Execute for the category
                            $sqlQuery = "SELECT id, category_name FROM tbl_category WHERE category_name NOT LIKE 'All' ORDER BY category_name";
                            $sqlQueryResult = $connection->query($sqlQuery);

                            while($categoryData = $sqlQueryResult->fetch_assoc()) {
                                $categoryId = $categoryData["id"];
                                $categoryName = $categoryData["category_name"];

                                echo "
                                    <option value='$categoryId|$categoryName' name='category'>$categoryName</option>
                                ";
                            }
                          ?>
                      </select>
                </div>
                <div class="col text-center">
                    <button type="submit" class="btn btn-primary mt-2">SELECT CATEGORY</button>
                </div>
            </form>
        </div>
    </body>
</html>