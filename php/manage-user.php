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
        <title><?php echo $_SESSION['siteName']?> | Manage User</title>

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
            if(isset($_POST['delete'])) {
                include_once("../modals/modal.php");
                $userEmail = $_POST['delete'];
                ?>

                <script>
                    document.getElementById("myModalLabelh5").innerHTML = "Delete | <?php echo $userEmail;?>"
                    document.getElementById("myModalOutput").innerHTML = "Are you Sure you want to delete <?php echo $userEmail;?>?"
                    document.getElementById("myModalButtons").innerHTML = ""
                    document.getElementById("myModalButtons").insertAdjacentHTML("afterbegin", "<form action='' method='post' id='myModalForm'>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='submit' name='delete-action' class='btn btn-danger me-2' value='<?php echo $userEmail;?>'>Yes</button>")
                    document.getElementById("myModalForm").insertAdjacentHTML("beforeend", "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>")
                    myModal.show()
                </script>

                <?php
                
            } elseif(isset($_POST['delete-action'])) {
                $userEmail = $_POST['delete-action'];
                
                //Use to delete the picture from the img/profile folder
                //Run this first before deleting the whole column from the table
                $queryProfile = "SELECT profile_picture FROM tbl_users WHERE email = '$userEmail'";
                $queryProfileResult = $connection->query($queryProfile);
                $profileResult = $queryProfileResult->fetch_assoc();
                $path = "../img/profile/" . $profileResult['profile_picture'];
                
                
                //Delete the profile picture if they change from an image that is not a default
                //Also stop the user from being able to delete the default profile
                if($profileResult['profile_picture'] != "default.png") {
                    unlink($path);
                }

                //Ready the query and execute it to delete the category
                $deleteQuery = "DELETE FROM tbl_users WHERE email = '$userEmail'";
                $deleteCategory = $connection->query($deleteQuery);

                // Check for error during database execution
                if($deleteCategory) {
                    ?>

                    <script>
                        document.getElementById("myModalOutput").innerHTML = "<?php echo $userEmail; ?> Successfuly Deleted"
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
            }
        ?>

        <!-- Container for the table of the user list -->
        <div class="container p-3 mb-2 bg-dark text-white table-responsive rounded-3">
            <h1 class="text-center mb-2">User List</h1>
            <table class="table table-dark table-striped align-middle table-bordered table-responsive">
                <thead class="text-center">
                    <tr>
                        <th>Profile Picture</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Cellphone Number</th>
                        <th>User Type</th>
                        <th>Control</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php

                        //Query for users in the tbl_users
                        $sqlQuery = "SELECT DISTINCT tbl_users.* FROM tbl_users LEFT JOIN tbl_history ON tbl_users.id = tbl_history.user_id ORDER BY CASE
                                                    WHEN tbl_users.user_type = 'admin' AND tbl_history.id > 0 THEN 1
                                                    WHEN tbl_users.user_type = 'admin' AND tbl_history.id IS NULL THEN 2
                                                    WHEN tbl_users.user_type = 'customer' AND tbl_history.id > 0 THEN 3
                                                    WHEN tbl_users.user_type = 'customer' AND tbl_history.id IS NULL THEN 4
                                                    ELSE 5
                                                END";
                        $sqlQueryResult = $connection->query($sqlQuery);

                        //This is a loop for table body list
                        while($userData = $sqlQueryResult->fetch_assoc()) {
                            echo "
                            <tr>
                                <td>
                                    <a href='profile.php?id={$userData['id']}'><img src='../img/profile/{$userData['profile_picture']}' alt='Profile Unavailable' class='rounded-3' style='width: 5rem; height: 5rem;'></a>
                                </td>
                                <td>
                                    {$userData['first_name']} {$userData['middle_name']} {$userData['last_name']}
                                </td>
                                <td>
                                    {$userData['address']}, {$userData['city']}, {$userData['region']}, {$userData['zip_code']}
                                </td>
                                <td>
                                    {$userData['email']}
                                </td>
                                <td>
                                    {$userData['phone_number']}
                                </td>
                                <td>
                                    {$userData['user_type']}
                                </td>";

                                $userId = $userData['id'];

                                $queryHistory = "SELECT count(DISTINCT id) AS number FROM tbl_history WHERE (user_id = $userId) AND (status = 'pending' OR status = 'processing')";
                                $queryHistoryResult = $connection->query($queryHistory);
                                $queryHistoryResultFetch = $queryHistoryResult->fetch_assoc();
                                @$orderNumber = $queryHistoryResultFetch['number'];

                                //The admin cannot delete a fellow admin user
                                if(!($userData['user_type'] === 'admin')) {
                                    echo"
                                    <td class='col-2'>
                                        <form action='' method='post'>
                                            <button type='submit' name='delete' class='btn btn-danger' value='{$userData['email']}'>Delete</button>
                                            <a class='btn btn-primary position-relative' href='order-status.php?id={$userData['id']}' role='button'>Orders ".
                                            ($orderNumber == 0 ? "</a>" : "<span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary'>$orderNumber<span class='visually-hidden'>Pending Orders</span></span></a>")
                                            ."
                                        </form>
                                    </td>
                                </tr>";
                                } else {
                                    echo"
                                    <td class='col-2'>
                                        <a class='btn btn-secondary disabled' href='#' role='button'>Delete</a>
                                        <a class='btn btn-primary position-relative disabled' href='#' role='button'>Orders".
                                        ($orderNumber == 0 ? "</a>" : "<span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary'>$orderNumber<span class='visually-hidden'>Pending Orders</span></span></a>")
                                        ."
                                    </td>
                                </tr>";
                                }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>