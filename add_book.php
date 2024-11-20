<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publication_year = $_POST['publication_year'];
    $price = $_POST['price'];

    // Query to insert a new book
    $query = "INSERT INTO Books (title, author, publication_year, price) 
              VALUES (:title, :author, :publication_year, :price)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([
            ':title' => $title,
            ':author' => $author,
            ':publication_year' => $publication_year,
            ':price' => $price
        ]);

        echo "Book added successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
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
    <form method="POST" action="">
        <label>Title:</label>
        <input type="text" name="title" required><br>

        <label>Author:</label>
        <input type="text" name="author" required><br>

        <label>Publication Year:</label>
        <input type="number" name="publication_year" required><br>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required><br>

        <button type="submit">Add Book</button>
    </form>
</body>
</html>
