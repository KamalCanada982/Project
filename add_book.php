<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publication_year = $_POST['publication_year'];
    $price = $_POST['price'];
    $image_filename = null; // Default no image

    // Handle file upload if an image is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $tmpFilePath = $_FILES['image']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $filePath = $uploadDir . $fileName;

        // Validate "image-ness"
        $imageInfo = getimagesize($tmpFilePath);
        if ($imageInfo !== false) {
            // Resize the image
            $resizedFilePath = $uploadDir . 'resized_' . $fileName;
            resizeImage($tmpFilePath, $resizedFilePath, 300, 300);

            // Move the resized image and set its filename
            if (move_uploaded_file($tmpFilePath, $resizedFilePath)) {
                $image_filename = 'resized_' . $fileName;
            }
        } else {
            echo "Uploaded file is not a valid image.";
        }
    }

    // Insert the book data
$query = "INSERT INTO Books (title, author, publication_year, price, image) 
VALUES (:title, :author, :publication_year, :price, :image)";
$stmt = $pdo->prepare($query);

try {
$stmt->execute([
':title' => $title,
':author' => $author,
':publication_year' => $publication_year,
':price' => $price,
':image' => $image_filename
]);
echo "Book added successfully!";
} catch (PDOException $e) {
echo "Error: " . $e->getMessage();
}
}

/**
 * Resize an image using PHP GD library.
 */
function resizeImage($srcFilePath, $destFilePath, $width, $height) {
    $imageInfo = getimagesize($srcFilePath);
    $srcWidth = $imageInfo[0];
    $srcHeight = $imageInfo[1];

    // Create a blank canvas for the resized image
    $destImage = imagecreatetruecolor($width, $height);

    // Load the original image
    switch ($imageInfo['mime']) {
        case 'image/jpeg':
            $srcImage = imagecreatefromjpeg($srcFilePath);
            break;
        case 'image/png':
            $srcImage = imagecreatefrompng($srcFilePath);
            break;
        case 'image/gif':
            $srcImage = imagecreatefromgif($srcFilePath);
            break;
        default:
            echo "Unsupported image type.";
            return false;
    }

    // Resize the original image into the blank canvas
    imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);

    // Save the resized image
    switch ($imageInfo['mime']) {
        case 'image/jpeg':
            imagejpeg($destImage, $destFilePath);
            break;
        case 'image/png':
            imagepng($destImage, $destFilePath);
            break;
        case 'image/gif':
            imagegif($destImage, $destFilePath);
            break;
    }

    // Free memory
    imagedestroy($srcImage);
    imagedestroy($destImage);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="add_edit_book.css">
</head>
<body>
    <h2>Add a New Book</h2>
    <form method="POST" action="" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" required><br>

    <label>Author:</label>
    <input type="text" name="author" required><br>

    <label>Publication Year:</label>
    <input type="number" name="publication_year" required><br>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" required><br>

    <label>Image:</label>
    <input type="file" name="image" accept="image/*"><br>

    <button type="submit">Add Book</button>
</form>

</body>
</html>
