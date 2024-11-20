<?php
require_once 'db_connect.php';
session_start();

// Check if the logged-in user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Only admins can access this page.";
    exit;
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Delete the user from the database
    $query = "DELETE FROM Users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $user_id]);

    $success = "User deleted successfully!";
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "User ID is missing.";
    exit;
}
