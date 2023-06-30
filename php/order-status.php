<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");

    //Check if the current user is allowed to access the webpage
    //Only the admin can access this webpage
    if(($_SESSION['userType'] != "admin") || (empty($_GET['id']))) {
        header("Location: ../index.php");
    }

    //Get the id from the url
    $userId = $_GET['id'];

    //Query and Execute for the user information
    $querySelectEmail = "SELECT email FROM tbl_users WHERE id = $userId";
    $querySelectEmailResult = $connection->query($querySelectEmail);

    $emailResult = $querySelectEmailResult->fetch_assoc();

    $userEmail = $emailResult["email"];

    //Redirect the user if the id is invalid
    if(is_null($userEmail)) {
        header("Location: manage-user.php");
    }

    //For the last Order Id
    $querySelectLast = "SELECT MIN(id) AS last FROM tbl_history WHERE user_id = $userId";
    $querySelectLastResult = $connection->query($querySelectLast);
    $Last = $querySelectLastResult->fetch_assoc();
    $lastId = $Last["last"];
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Title of the site  is set in SESSION from the database.php -->
        <title><?php echo $_SESSION['siteName']?> | Order Status</title>

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
            // If the User Click the Register Button
            if(isset($_POST['btnUpdate'])) {
                $orderStatus = $_POST["orderStatus"];
                $orderId = $_POST["orderId"];

                $status = array("pending", "processing", "delivered", "canceled");

                if(in_array($orderStatus, $status)) {
                    $sqlUpdate = "UPDATE tbl_history SET status = '$orderStatus' WHERE id = '$orderId'";
                    $sqlUpdateResult = $connection->query($sqlUpdate);

                    if(!$sqlUpdateResult) {
                        include_once("../modals/modal.php");
                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "Error occured. Please try again later. <br><?php echo $connection->error; ?>"</script>
                        <script>myModal.show()</script>
                        <?php 
                    }
                }
            }
        ?>

        <!-- Container  -->
        <div class="container p-3 mb-2 bg-normal-92 table-responsive text-white rounded-3 w-50">
            <div class="row g-0">
                <div class="col-sm-6 col-md-8 ps-3">
                    <h1><?php echo $userEmail?></h1>
                </div>
                <div class="col-6 col-md-4 text-end pe-3">
                    <h1><a href="manage-user.php" class="text-reset text-decoration-none"><i class="bi bi-arrow-counterclockwise"></i>Back</a></h1>
                </div>
            </div>
            <?php

                //Query and Execute for the history information
                $querySelectHistory = "SELECT * FROM tbl_history WHERE user_id = {$_GET['id']} ORDER BY id DESC";
                $querySelectHistoryResult = $connection->query($querySelectHistory);

                //Set a null to hold the Order Id
                $historyOrderId = null;
                $isEmpty = true;
                $isFirst = true;
                //Uses loop to echo all the items the user selected
                while($historyData = $querySelectHistoryResult->fetch_assoc()) {
                    //Variables
                    $historyItem = $historyData["item_id"];
                    $historyPicture = $historyData["item_picture"];
                    $historyQuantity = $historyData["item_quantity"];
                    $historyName = $historyData["item_name"];
                    $historyPrice = $historyData["item_price"];
                    $historyPriceFormat = number_format($historyPrice, 2, '.', ',');
                    $historyTime = strtotime($historyData["time"]);
                    $historyTimeFormatted = date("F j\, Y \of A g\:i", $historyTime);
                    $historyStatus = $historyData["status"];
                    $historyPQ = $historyQuantity * $historyPrice;
                    $historyPQFormat = number_format($historyPQ, 2, '.', ',');

                    //To Check if there is Data from the tbl_history
                    $isEmpty = false;

                    //Uses this so that it would be Group by the Order Id
                    if($historyData["id"] != $historyOrderId) {//1 != null
                        $oldId = $historyOrderId;//null
                        $historyOrderId = $historyData["id"];//1

                        //The first oldId would always be null so we need to ignore that
                        if($oldId != null) {
                            //For the Grand Total and Total Items
                            $querySelectTotal = "SELECT SUM(item_price * item_quantity) AS totalPrice, SUM(item_quantity) AS totalQuantity FROM tbl_history WHERE id = $oldId";
                            $querySelectTotalResult = $connection->query($querySelectTotal);
                            $historyTotal = $querySelectTotalResult->fetch_assoc();
                            $historyTotalPrice = $historyTotal["totalPrice"];
                            $historyTotalPriceFormat = number_format($historyTotalPrice, 2, '.', ',');
                            $historyTotalQuantity = $historyTotal["totalQuantity"];
                        }

                        if($isFirst == false) {
                            echo "
                                </tbody>
                                <tfoot class='text-center'>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class='text-end h5'>Grand Total:</td>
                                        <td class='h5'>₱ $historyTotalPriceFormat</td>
                                    </tr>
                                </tfoot>
                                <tfoot class='text-center'>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class='text-end h5'>Total Items:</td>
                                        <td class='h5'>$historyTotalQuantity</td>
                                    </tr>
                                </tfoot>
                                </table>

                                <div class='card-footer text-center h5 m-0 text-white'>
                                    Time Purchase: $historyTimeFormatted
                                </div>
                                </div>
                                <br>
                            ";
                        }

                        echo "
                            <div class='card history-color mb-5'>
                                <div class='card-header row'>
                                    <p class='text-start h3 ps-4 m-0 col text-white'>Order Id: $historyOrderId</p>
                                    <p class='text-end h3 pe-4 m-0 col text-white'>Order Status: ". ($historyStatus == 'pending' ? '<span class="badge bg-warning text-dark">Pending</span>' :
                                        ($historyStatus == 'processing' ? '<span class="badge bg-info text-dark">Processing</span>' :
                                            ($historyStatus == 'delivered' ? '<span class="badge bg-success text-dark">Delivered</span>' :
                                                '<span class="badge bg-secondary text-dark">Cancelled</span>')))
                                    ."</p>
                                </div>
                                <form action='' method='post'>
                                    <div class='d-flex justify-content-center me-4 mt-3 mb-3'>
                                        <div class='order-1'>
                                            <h4 class='text-end text-white'>Update Order Status:</h4>
                                        </div>
                                        <div class='order-2 me-2 ms-2'>
                                            <select class='form-select form-select-sm bg-dark text-white' name='orderStatus' style='width: 11rem;'>
                                                <option ". ($historyStatus == 'pending' ? 'selected' : '') ." value='pending'>Pending</option>
                                                <option ". ($historyStatus == 'processing' ? 'selected' : '') ." value='processing'>Processing</option>
                                                <option ". ($historyStatus == 'delivered' ? 'selected' : '') ." value='delivered'>Delivered</option>
                                                <option ". ($historyStatus == 'canceled' ? 'selected' : '') ." value='canceled'>Canceled</option>
                                            </select>
                                        </div>
                                        <div class='order-3'>
                                            <input type='hidden' name='orderId' value='$historyOrderId'>
                                            <input type='hidden' name='userId' value='$userId'>
                                            <input type='hidden' name='userEmail' value='$userEmail'>
                                            <button type='submit' class='btn btn-primary btn-sm' style='width: 10rem;' name='btnUpdate'>Update Status</button>
                                        </div>
                                    </div>
                                </form>
                                <table class='table table-dark  border-white align-middle m-0'>
                                    <thead class='text-center'>
                                        <tr>
                                            <th class='col-1 border'>PICTURE</th>
                                            <th class='col-2 border'>NAME</th>
                                            <th class='col-1 border'>PRICE</th>
                                            <th class='col-1 border'>QUANTITY</th>
                                            <th class='col-1 border'>TOTAL</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                        ";
                    }

                    echo "
                        <tr class='text-center'>
                            <td class='border-start border-end'><a href='item.php?id=$historyItem'><img src='../img/items/$historyPicture' class='rounded mx-auto d-block img-fluid cart-img' alt='$historyName'></a></td>
                            <td class='h5 border-start border-end'><a href='item.php?id=$historyItem' class='text-reset text-decoration-none'>$historyName</a></td>
                            <td class='h5 border-start border-end'>₱ $historyPriceFormat</td>
                            <td class='h5 border-start border-end'>$historyQuantity</td>
                            <td class='h5 border-start border-end'>₱ $historyPQFormat</td>
                        </tr>
                    ";
                    //Check if the fetch is the first data
                    if($isFirst == true) {
                        $isFirst = false;
                    }
                }
                //Show an Error for History is Empty
                if($isEmpty) {
                    echo "
                        <div class='alert alert-warning text-center h2' role='alert'>
                            History is Empty.
                        </div>";
                } else {
                    $querySelectTotal = "SELECT SUM(item_price * item_quantity) AS totalPrice, SUM(item_quantity) AS totalQuantity FROM tbl_history WHERE id = $lastId";
                    $querySelectTotalResult = $connection->query($querySelectTotal);
                    $historyTotal = $querySelectTotalResult->fetch_assoc();
                    $historyTotalPrice = $historyTotal["totalPrice"];
                    $historyTotalPriceFormat = number_format($historyTotalPrice, 2, '.', ',');
                    $historyTotalQuantity = $historyTotal["totalQuantity"];

                    echo "
                        </tbody>
                        <tfoot class='text-center'>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class='text-end h5'>Grand Total:</td>
                                <td class='h5'>₱ $historyTotalPriceFormat</td>
                            </tr>
                        </tfoot>
                        <tfoot class='text-center'>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class='text-end h5'>Total Items:</td>
                                <td class='h5'>$historyTotalQuantity</td>
                            </tr>
                        </tfoot>
                        </table>
                        <div class='card-footer text-center h5 m-0 text-white'>
                            Time Purchase: $historyTimeFormatted
                        </div>
                    ";
                }
            ?>
        </div>
    </body>
</html>