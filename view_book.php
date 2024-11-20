<?php
require_once 'db_connect.php';

if (!isset($_GET['book_id']) || empty($_GET['book_id'])) {
    echo "Invalid book ID.";
    exit;
}

$book_id = $_GET['book_id'];

// Fetch book details from the database
$query = "SELECT * FROM Books WHERE book_id = :book_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':book_id' => $book_id]);
$book = $stmt->fetch();

if (!$book) {
    echo "Book not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        .book-details {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .book-details h1 {
            font-size: 2rem;
            color: #333;
        }

        .book-details p {
            margin: 10px 0;
            line-height: 1.6;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($book['title']) ?></h1>
    </header>

    <div class="book-details">
        <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
        <p><strong>Publication Year:</strong> <?= htmlspecialchars($book['publication_year']) ?></p>
        <p><strong>Price:</strong> $<?= htmlspecialchars($book['price']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($book['description'] ?? 'No description available.') ?></p>
        <a href="index.php">Back to Book List</a>
    </div>
</body>
</html>
