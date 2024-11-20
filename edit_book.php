<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

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

    echo "Book updated successfully!";
    header("Location: admin_dashboard.php");
    exit;
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
    <form method="POST" action="">
        <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
        <label>Title:</label>
        <input type="text" name="title" value="<?= $book['title'] ?>" required><br>
        <label>Author:</label>
        <input type="text" name="author" value="<?= $book['author'] ?>" required><br>
        <label>Publication Year:</label>
        <input type="number" name="publication_year" value="<?= $book['publication_year'] ?>" required><br>
        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?= $book['price'] ?>" required><br>
        <button type="submit">Update Book</button>
    </form>
</body>
</html>
