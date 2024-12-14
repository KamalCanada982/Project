<?php
require_once 'db_connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to comment.";
    exit;
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['content']);
    $captcha = trim($_POST['captcha']);

    // Validate CAPTCHA
    if (!isset($_SESSION['captcha']) || strcasecmp($captcha, $_SESSION['captcha']) !== 0) {
        echo "Invalid CAPTCHA. Please go back and try again.";
        exit;
    }

    // Clear the used CAPTCHA to prevent reuse
    unset($_SESSION['captcha']);

    // Insert the comment into the database
    $query = "INSERT INTO Comments (book_id, user_id, content) VALUES (:book_id, :user_id, :content)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':book_id' => $book_id,
        ':user_id' => $user_id,
        ':content' => $content,
    ]);

    // Redirect to the book's page
    header("Location: view_book.php?book_id=$book_id");
    exit;
}
?>
