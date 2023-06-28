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
        <?php include_once("../inc/navBar.php"); ?>

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
                                            <a href='item-edit.php?id=$itemId' class='link-primary'>Edit</a> |
                                            <a href='item-delete.php?id=$itemId' class='link-danger'> Delete</a>
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