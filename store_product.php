<?php
session_start();
require_once 'db.php';

function rearrange_files($files)
{
    $file_array = [];
    foreach ($files as $file_key => $file) {
        foreach ($file as $index => $file_value) {
            $file_array[$index][$file_key] = $file_value;
        }
    }
    return $file_array;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /** array variable to hold errors */
    $errors = [];

    $product_id   = $_POST['product_id'];
    $product_images  = $_FILES['product_images'];
    /** Add form validation */
    if (empty($product_images)) {
        $errors[] = 'Product invoice file required';
    }
    if (empty($product_id)) {
        $errors[] = 'Select product you want to add image';
    }
    /** Check if the product exists in your database */
    $product_stmt = $pdo->prepare("
        SELECT
            id, name
        FROM
            `products`
        WHERE
            id = :product_id
    ");
    $product_stmt->execute([
        ':product_id'     => $product_id,
    ]);
    $product = $product_stmt->fetchObject();
    if (!$product) {
        $errors[] = 'Selected product does not exist!';
    }
    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
    }

    /** $_FILES will have the upload file details in PHP */
    $arranged_files = rearrange_files($_FILES['product_images']);
    
    $file_extensions   = ['pdf', 'xls', 'jpeg', 'jpg', 'png', 'svg', 'webp'];
    
    foreach ($arranged_files as $product_image) {
        /** I am using pathinfo to fetch the details of the PHP File */
        $file_name          = $product_image['name'];
        $file_size          = $product_image['size'];
        $file_tmp           = $product_image['tmp_name'];
        $pathinfo           = pathinfo($file_name);
        $extension          = $pathinfo['extension'];
        
        /** File strict validations */
        /** File exists */
        if (!file_exists($file_tmp)) {
            $errors[] = 'File your trying to upload not exists';
        }

        /** Check if the was uploaded only */
        if (!is_uploaded_file($file_tmp)) {
            $errors[] = 'File not uploaded properly';
        }

        /** Check for the file size 1024 * 1024 is 1 MB & 1024 KB */
        if ($file_size > (1024 * 1024)) {
            $errors[] = 'Uploaded file is greater than 1MB';
        }

        /** Check File extensions */
        if (!in_array($extension, $file_extensions)) {
            $errors[] = 'File allowed extensions '. implode(', ', $file_extensions);
        }

        if (count($errors) > 0) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php');
            exit;
        }

        /** Since I want to rename the File I need its extension
         * which will be obtained with above $phpinfo variable
         * */
        /** generate random image name */
        $new_file_name = rand(0, 10000000).time().md5(time()).'.'.$extension;
        move_uploaded_file($file_tmp, './uploads/product_images/'. $new_file_name);
        
        $product_image = $pdo->prepare("
            INSERT INTO 
                `product_images` (`product_id`, `product_image`)
            VALUES
                (:product_id, :product_image)
        ")
        ->execute([
            ':product_id'       => $product->id,
            ':product_image'    => $new_file_name,
        ]);
    }
    $_SESSION['success'] = 'Products added successfully';
    header('Location: index.php');
} else {
    header('Location: index.php');
}
