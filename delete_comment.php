<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Unauthorized action.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment_id'];

    $query = "DELETE FROM Comments WHERE comment_id = :comment_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':comment_id' => $comment_id]);

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
