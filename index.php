<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Demo</title>
    <style>
        body{ box-sizing: border-box }
        .wrapper{ width: 250px; margin: auto; }
        form input, form select{ width: 100%; padding: 3px; }
        .error{ font-style: italic; color: red; }
        .success{ font-style: italic; color: green; }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php
            $errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
            if (isset($errors)) {
                foreach ($errors as $error) {
                    echo '<p class="error">'. $error .'</p>';
                }
            }

            $success = $_SESSION['success'];
            unset($_SESSION['success']);
            if (isset($success)) {
                foreach ($success as $succ) {
                    echo '<p class="success">'. $succ .'</p>';
                }
            }
        ?>
        <form action="store_product.php" method="post" enctype="multipart/form-data">
            <div>
                <label for="product_images">Select Product</label> <br>
                <!-- 
                    Basically you get the product list from database.
                    For the sake of demonstration I am hard coding
                -->
                <select name="product_id" id="product_id">
                    <option value="1">Product 1</option>
                    <option value="2">Product 2</option>
                </select>
            </div> <br>
            <div>
                <label for="product_images">Product Images</label> <br>
                <input type="file" name="product_images[]" id="product_images" multiple>
            </div> <br>
            <div>
                <input type="submit" value="Upload Product Images">
            </div>
        </form>
    </div>
</body>
</html>