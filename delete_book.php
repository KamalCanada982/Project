<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    $query = "DELETE FROM Books WHERE book_id = :book_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':book_id' => $book_id]);

    echo "Book deleted successfully!";
    header("Location: admin_dashboard.php");
    exit;
}
?>
