<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");

?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Title of the site  is set in SESSION from the database.php -->
        <title><?php echo $_SESSION['siteName']?> | Home</title>

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

        <!-- Container for the whole list of categories -->
        <div class="container p-3 mb-2 bg-normal-92 text-white rounded-3">
            <h1 class="text-center mb-2 text-white">Home</h1>
            <div class="row row-cols-1 row-cols-md-3 g-4 text-center justify-content-md-center">
                <?php
                    //Query and Execute for the user information
                    $sqlQuery = "SELECT * FROM tbl_category ORDER BY CASE category_name WHEN 'All' THEN 1 ELSE 2 END;";
                    $sqlQueryResult = $connection->query($sqlQuery);
                    while($categoryData = $sqlQueryResult->fetch_assoc()){
                        $categoryName = $categoryData['category_name'];
                        $categoryPicture = $categoryData['category_picture'];
                        echo "
                        <div class='card mb-3 ms-2 border border-secondary border-3 card-color category-card p-0'>
                            <div class='row g-0'>
                                <div class='col-md-5 d-flex align-items-center'>
                                  <a href='itemList.php?category=$categoryName'><img class='img-fluid category-card-img border-end border-3 border-secondary' src='../img/category/$categoryPicture' alt='Image Unavailable'></a>
                                </div>
                                <div class='col-md-7 d-flex align-items-center'>
                                  <div class='card-body text-wrap text-break'>
                                    <h1 class='card-title line-clamp-category p-1'><a href='itemList.php?category=$categoryName' class='text-reset text-decoration-none'>$categoryName</a></h1>
                                  </div>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                ?>
            </div>
        </div>
    </body>
</html>