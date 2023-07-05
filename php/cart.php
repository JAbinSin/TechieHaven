<?php
    //Include the database to the webpage to access it
    include_once("../inc/database.php");
    ob_start();

    //Check if the current user is allowed to access the webpage
    //Only the admin and customer can access this webpage
    if(!isset($_SESSION['userType'])) {
        header("Location: ../index.php");
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Title of the site  is set in SESSION from the database.php -->
        <title><?php echo $_SESSION['siteName']?> | Cart</title>

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
            // If the Customer Click the +
            if(isset($_POST['btnUpdate'])) {
                include("../modals/modal.php");
                $cartId = $_POST['btnUpdate'];
            
                $cartQty = $_POST["Qty_$cartId"];

                //Check if the input is blank
                //This is just a safety measure if it happens
                if(empty($cartId)) {
                    $error['cartId'] = "Cart Id is Empty.";
                }

                //If there is no error update the item in the cart
                if(empty($error)) {

                    //So that the Quantity can't exceed the maximum limit of 99
                    if($cartQty >= 99) {
                        $cartQty = 99;
                    }
                    //So that the Quantity can't be below 1
                    if($cartQty < 1) {
                        $cartQty = 1;
                    }

                    $sqlUpdate = "UPDATE tbl_cart SET quantity = '$cartQty' WHERE id = '$cartId'";
                    $sqlUpdateResult = $connection->query($sqlUpdate);

                    if(!$sqlUpdateResult) {
                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "Error occured. Please try again later. <br><?php echo $connection->error; ?>"</script>
                        <script>myModal.show()</script>
                        <?php 
                    }
                }
                header("Location: ");
            } elseif(isset($_POST['btnRemove'])) {
                include("../modals/modal.php");

                $cartId = $_POST['btnRemove'];
                $sqlQuery = "SELECT * FROM tbl_cart INNER JOIN tbl_items ON tbl_cart.item_id = tbl_items.id WHERE tbl_cart.id = $cartId";
                $sqlQueryResult = $connection->query($sqlQuery);
                $cartData = $sqlQueryResult->fetch_assoc();

                ?>

                <script>
                    document.getElementById("myModalLabelh5").innerHTML = "Remove | <?php echo $cartData['item_name'];?>"
                    document.getElementById("myModalOutput").innerHTML = "Are you Sure you want to delete <?php echo $cartData['item_name'];?>?"
                    document.getElementById("myModalButtons").innerHTML = ""
                    document.getElementById("myModalButtons").insertAdjacentHTML("afterbegin", "<form action='' method='post' id='myModalForm'>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='submit' name='delete-action' class='btn btn-danger me-2' value='<?php echo $cartId;?>'>Yes</button>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>")
                    myModal.show()
                </script>

                <?php
                
            } elseif(isset($_POST['delete-action'])) {
                $cartId = $_POST['delete-action'];

                //Ready the query and execute it to delete the cart
                $deleteQuery = "DELETE FROM tbl_cart WHERE id = '$cartId'";
                $deleteCategory = $connection->query($deleteQuery);
                header("Location: ");
            } elseif(isset($_POST['btnClear'])) {
                include("../modals/modal.php");
                ?>

                <script>
                    document.getElementById("myModalLabelh5").innerHTML = "Clear Cart"
                    document.getElementById("myModalOutput").innerHTML = "Are you Sure you want to Clear your Cart?"
                    document.getElementById("myModalButtons").innerHTML = ""
                    document.getElementById("myModalButtons").insertAdjacentHTML("afterbegin", "<form action='' method='post' id='myModalForm'>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='submit' name='clear-action' class='btn btn-danger me-2' value='Yes'>Yes</button>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>")
                    myModal.show()
                </script>

                <?php

            } elseif(isset($_POST['clear-action'])) {
                $userId = $_SESSION['userId'];

                //Ready the query and execute it to delete the cart
                $deleteQuery = "DELETE FROM tbl_cart WHERE user_id = '$userId'";
                $deleteCategory = $connection->query($deleteQuery);
                header("Location: ");
            } elseif(isset($_POST['btnBuy'])) {
                include("../modals/modal.php");
                ?>

                <script>
                    document.getElementById("myModalLabelh5").innerHTML = "Buy Cart"
                    document.getElementById("myModalOutput").innerHTML = "Are you Sure you want to Buy all the item in the Cart?"
                    document.getElementById("myModalButtons").innerHTML = ""
                    document.getElementById("myModalButtons").insertAdjacentHTML("afterbegin", "<form action='' method='post' id='myModalForm'>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='submit' name='buy-action' class='btn btn-success me-2' value='Yes'>Yes</button>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>")
                    myModal.show()
                </script>

                <?php
            } elseif(isset($_POST['buy-action'])) {
                $userId = $_SESSION['userId'];
                $itemStatus = "pending";
                $errorCheck = false;

                $queryCart = "SELECT * FROM tbl_cart INNER JOIN tbl_items ON tbl_cart.item_id = tbl_items.id WHERE tbl_cart.user_id = $userId";
                $queryCartResult = $connection->query($queryCart);
                while($cartData = $queryCartResult->fetch_assoc()) {
                    $itemPrice = $cartData['item_price'];
                    $itemPicture = $cartData['item_picture'];
                    $itemName = $cartData['item_name'];
                    $itemId = $cartData['item_id'];
                    $itemQuantity = $cartData['quantity'];
                    $itemPrice = $cartData['item_price'];
                    $itemTime = date("Y-m-d H:i:s");
                    

                    $sqlInsert = "INSERT INTO tbl_history(user_id, item_picture, item_name, item_id, item_quantity, item_price, time, status)
                    VALUES('$userId', '$itemPicture', '$itemName', '$itemId', '$itemQuantity', '$itemPrice', '$itemTime','$itemStatus')";

                    $sqlInsertResult = $connection->query($sqlInsert);

                    if(!$sqlInsertResult) {
                        $errorCheck = true;
                        ?>
                        <script>document.getElementById("myModalOutput").innerHTML = "Error occured. Please try again later. <br><?php echo $connection->error; ?>"</script>
                        <script>myModal.show()</script>
                        <?php 
                    }
                }
                if(!$errorCheck) {
                    $userId = $_SESSION['userId'];

                    //Ready the query and execute it to delete the cart
                    $deleteQuery = "DELETE FROM tbl_cart WHERE user_id = '$userId'";
                    $deleteCategory = $connection->query($deleteQuery);
                    header("Location: history.php");
                }
            }
        ?>

        <?php 
            
        ?>

        <!-- Container  -->
        <div class="container p-3 mb-2 bg-normal-92 text-white rounded-3 w-75 table-responsive">
            <h1 class="text-start mb-2">Cart</h1>

            <?php
            //Check if the Cart is Empty
            if($cartQuantity < 1) {
                echo "
                    <div class='alert alert-warning text-center' role='alert'>
                        <h2>Cart is Empty.</h2>
                    </div>";
            } else {
                echo "
                <form action='' method='post' id=formCart>
                    <table class='table table-dark  border-white align-middle'>
                        <thead class='text-center'>
                            <tr>
                                <th class='col-1'>REMOVE</th>
                                <th class='col-1'>PICTURE</th>
                                <th class='col-2'>NAME</th>
                                <th class='col-1'>PRICE</th>
                                <th class='col-1'>QUANTITY</th>
                                <th class='col-1'>TOTAL</th>
                            </tr>
                        </thead>

                        <tbody>
                ";

                //If the Cart is not empty, list all the current item in cart
                $userId = $_SESSION['userId'];
                $queryCart = "SELECT * FROM tbl_cart WHERE user_id = $userId";
                $queryCartResult = $connection->query($queryCart);
                $i = 0;
                $totalPrice = 0;
                while($queryCartResultFetch = $queryCartResult->fetch_assoc()) {
                    $cartId = $queryCartResultFetch['id'];
                    $itemId = $queryCartResultFetch['item_id'];
                    $itemQuantity = $queryCartResultFetch['quantity'];

                    $itemQuery = "SELECT * FROM tbl_items WHERE id = $itemId";
                    $itemQueryResult = $connection->query($itemQuery);
                    $itemData = $itemQueryResult->fetch_assoc();

                    $itemName = $itemData['item_name'];
                    $itemDescription = $itemData['item_description'];
                    $itemPrice = $itemData['item_price'] * $itemQuantity;
                    $itemUnitPrice = $itemData["item_price"];
                    $itemPicture = $itemData['item_picture'];
                    $itemCategory = $itemData['item_category'];
                    $totalPrice = $totalPrice + $itemPrice;

                    //Make variable to Number Format
                    $totalPriceNumber = number_format($totalPrice, 2, '.', ',');
                    $itemPriceNumber = number_format($itemPrice, 2, '.', ',');
                    $itemUnitPriceNumber = number_format($itemUnitPrice, 2, '.', ',');

                    echo "
                        <tr class='text-center'>
                            <td class='h2 border-start border-end border-white'><button type='submit' form='formCart' value='$cartId' name='btnRemove'><i class='bi bi-trash'></i></button></td>
                            <td class='border-start border-end'><a href='item.php?id=$cartId'><img src='../img/items/$itemPicture' class='rounded mx-auto d-block img-fluid cart-img' alt='$itemName'></a></td>
                            <td class='h5 border-start border-end'><a href='item.php?id=$cartId' class='text-reset text-decoration-none'>$itemName</a></td>
                            <td class='h5 border-start border-end'>₱ $itemUnitPriceNumber</td>
                            <td class='border-start border-end'>
                                <div class='quantity quantity-center'>
                                    ".($itemQuantity <= 1 ? "<button class='btn dec disabled' name='btnUpdate'>-</button>" : "<button class='btn dec' value='$cartId' name='btnUpdate'>-</button>")."
                                    <input class='quantity-input bg-dark h5' type='number' id='$i' name='Qty_$cartId' value='$itemQuantity' pattern='/^-?\d+\.?\d*$/' onKeyPress='if(this.value.length==2) return false;' onkeypress='return event.charCode >= 48 && event.charCode <= 57' title='Item Quantity' required>
                                    ".($itemQuantity >= 99 ? "<button class='btn inc disabled' name='btnUpdate'>+</button>" : "<button class='btn inc' value='$cartId' name='btnUpdate'>+</button>")."
                                    <input type='submit' hidden id='submitEnter$i' name='btnUpdate' class='submitEnter' value='$cartId'>
                                </div>
                            </td>
                            <td class='h5 border-start border-end'>₱ $itemPriceNumber</td>
                        </tr>
                    ";

                    $i++;
                }
                echo "
                        </tbody>

                        <tfoot class='text-center'>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class='text-end h5'>Grand Total:</td>
                                <td class='h5'>₱ $totalPriceNumber</td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
                ";
            }


            //If the cart is empty the option for Clear and Buy would not be visible/available
            if(!$cartQuantity < 1) {
                echo "
                    <form action='' method='post'>
                        <div class='col text-end me-5'>
                            <button class='btn btn-primary btn-danger btn-lg mt-3 ms-3' type='submit' name='btnClear' value='CLEAR'>CLEAR</button>
                            <button class='btn btn-primary btn-primary btn-lg mt-3 ms-3' type='submit' name='btnBuy' value='BUY'>BUY</button>
                        </div>
                    </form>
                ";
            }
            ?>
        </div>

        <script>
            //variables
            var incrementButton = document.getElementsByClassName('inc');
            var decrementButton = document.getElementsByClassName('dec');
            var input = document.getElementsByClassName('quantity-input');

            //for enter
            for(var i = 0; i < input.length; i++) {
                var enter = input[i];
                var id = "submitEnter" + i;

                enter.addEventListener("keyup", function(event) {
                    var buttonClicked = event.target;
                    var input = buttonClicked.parentElement.children[3];

                    if (event.keyCode === 13) {
                        event.preventDefault();
                        document.getElementById(id).click();
                    }
                });
            }

            //for increment button
            for(var i = 0; i < incrementButton.length; i++) {
                var button = incrementButton[i];
                button.addEventListener('click', function(event){
                    var buttonClicked = event.target;
                    var input = buttonClicked.parentElement.children[1];
                    var inputValue = input.value;

                    var newValue = parseInt(inputValue) + 1;

                    input.value = newValue;
                });
            }

            //for decrement button
            for(var i = 0; i < decrementButton.length; i++) {
                var button = decrementButton[i];
                button.addEventListener('click', function(event){
                    var buttonClicked = event.target;
                    var input = buttonClicked.parentElement.children[1];
                    var inputValue = input.value;

                    var newValue = parseInt(inputValue) - 1;

                    if(newValue > 0) {
                        input.value = newValue;
                    }
                });
            }
        </script>
    </body>
</html>