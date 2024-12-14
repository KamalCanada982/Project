<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Retrieve the book from the database to populate the form
    $query = "SELECT * FROM Books WHERE book_id = :book_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':book_id' => $book_id]);
    $book = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publication_year = $_POST['publication_year'];
    $price = $_POST['price'];

    // Retrieve the book from the database again to check the current image path
    $query = "SELECT * FROM Books WHERE book_id = :book_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':book_id' => $book_id]);
    $book = $stmt->fetch(); // Re-fetch the book after POST

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Resize and save image
        $imagePath = 'uploads/' . $_FILES['image']['name'];
        $imageTmpPath = $_FILES['image']['tmp_name'];

        // Get image details
        $imageInfo = getimagesize($imageTmpPath);
        $imageType = $imageInfo[2];
        
        if ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_PNG) {
            $image = null;
            if ($imageType == IMAGETYPE_JPEG) {
                $image = imagecreatefromjpeg($imageTmpPath);
            } elseif ($imageType == IMAGETYPE_PNG) {
                $image = imagecreatefrompng($imageTmpPath);
            }

            // Resize image (max width 500px)
            $maxWidth = 500;
            $width = imagesx($image);
            $height = imagesy($image);
            $newWidth = $maxWidth;
            $newHeight = ($height / $width) * $newWidth;

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            // Save the resized image
            if ($imageType == IMAGETYPE_JPEG) {
                imagejpeg($resizedImage, $imagePath, 90); // Quality 90
            } elseif ($imageType == IMAGETYPE_PNG) {
                imagepng($resizedImage, $imagePath, 9); // Compression level 9
            }

            // Clean up
            imagedestroy($image);
            imagedestroy($resizedImage);

            // Update book with image path
            $query = "UPDATE Books 
                      SET title = :title, author = :author, publication_year = :publication_year, price = :price, image = :image 
                      WHERE book_id = :book_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':title' => $title,
                ':author' => $author,
                ':publication_year' => $publication_year,
                ':price' => $price,
                ':image' => $imagePath,
                ':book_id' => $book_id
            ]);
        }
    } else {
        // Update book without new image
        $query = "UPDATE Books 
                  SET title = :title, author = :author, publication_year = :publication_year, price = :price 
                  WHERE book_id = :book_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':author' => $author,
            ':publication_year' => $publication_year,
            ':price' => $price,
            ':book_id' => $book_id
        ]);
    }

    // Handle image deletion
    if (isset($_POST['delete_image']) && $book['image']) {
        // Remove image from file system
        unlink($book['image']);  // Make sure 'image' contains the correct path

        // Remove image from database
        $query = "UPDATE Books 
                  SET image = NULL 
                  WHERE book_id = :book_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':book_id' => $book_id]);
                // Redirect after deleting image to reset form state
                header("Location: edit_book.php?book_id=" . $book_id);  // Refresh the page to show the updated state
        // Do not redirect immediately after deleting the image
        echo "Image deleted successfully!";
    }

    // Redirect only when the "Update Book" button is pressed (not delete button)
    if (!isset($_POST['delete_image'])) {
        echo "Book updated successfully!";
        header("Location: admin_dashboard.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="add_edit_book.css">
</head>
<body>
    <h2>Edit Book</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
        <label>Title:</label>
        <input type="text" name="title" value="<?= $book['title'] ?>" required><br>
        <label>Author:</label>
        <input type="text" name="author" value="<?= $book['author'] ?>" required><br>
        <label>Publication Year:</label>
        <input type="number" name="publication_year" value="<?= $book['publication_year'] ?>" required><br>
        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?= $book['price'] ?>" required><br>

    <!-- Image Upload Section -->
    <?php if ($book['image']): ?>
        <p>Current Image:</p>
        <img src="<?= $book['image'] ?>" alt="Book Image" style="width: 200px;">
        <br>
        <!-- Inline JavaScript confirmation dialog -->
        <button type="submit" name="delete_image" value="1" onclick="return confirm('Are you sure you want to delete this image? This action cannot be undone.')">Delete Image</button><br>
    <?php else: ?>
        <label>Upload Image:</label>
        <input type="file" name="image"><br>
    <?php endif; ?>



        <button type="submit">Update Book</button>
    </form>
</body>
</html>
