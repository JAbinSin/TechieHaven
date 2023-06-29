<!-- Modal -->
<?php 
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

<div class="modal fade" id="myModalItemEdit" tabindex="-1" aria-labelledby="myModalItemEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="modal-content" id='myModalItemEditContent'>
            <div class="modal-header">
                <h5 class="modal-title" id="myModalItemEditLabelh5">Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="myModalItemEditmOutput">
                <div class='mb-3'>
                    <label for='itemPicture' class='form-label'>Item Picture</label>
                    <input class='form-control' type='file' accept='image/*' name='itemPicture'>
                </div>
                <div class='mb-3'>
                    <label for='itemName' class='form-label'>Item Name</label>
                    <input type='text' class='form-control ' name='itemName' placeholder='<?php echo $itemName?>' value='<?php echo $itemName?>' required>
                    <input type='hidden' name='itemId' value='<?php echo $itemId;?>'>
                </div>
                <div class='mb-3'>
                    <label for='itemCategory' class='form-label'>Item Category</label>
                    <select class='form-select mt-1' name='itemCategory' required>
                        <option value='' disabled selected hidden>Please Choose...</option>
                        <?php
                            while($categoryData = $sqlQueryResult->fetch_assoc()) {
                                $categoryNameTmp = $categoryData["category_name"];
                                if($itemCategory == $categoryNameTmp) {
                                    echo "<option value='$categoryNameTmp' selected>$categoryNameTmp</option>";
                                } else {
                                    echo "<option value='$categoryNameTmp'>$categoryNameTmp</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class='mb-3'>
                    <label for='itemPrice' class='form-label'>Item Price</label>
                    <div class='input-group mb-3'>
                        <span class='input-group-text'>₱</span>
                        <input type='number' class='form-control' aria-label='Peso amount (with dot and two decimal places)' name='itemPrice' placeholder='e.g 25.00' step='.01' min='1' max='999999999' value='<?php echo $itemPrice?>' required>
                    </div>
                </div>
                <div class='mb-3'>
                    <label for='itemDescription' class='form-label'>Item Description</label>
                    <textarea class='form-control' rows='3' name='itemDescription' style='max-height: 15rem;' placeholder='<?php echo $itemDescription?>' required><?php echo $itemDescription?></textarea>
                </div>
            </div>
            <div class="modal-footer" id="myModalItemEditButtons">
                <button type="submit" name="edit-action" class="btn btn-primary btn-success">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    var myModalItemEdit = new bootstrap.Modal(document.getElementById('myModalItemEdit'))
</script>