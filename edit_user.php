<?php
require_once 'db_connect.php';
session_start();

// Check if the logged-in user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Only admins can access this page.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user details from the database
    $query = "SELECT * FROM Users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role']; // Role to assign to the user

    // Update the user's details in the database
    $query = "UPDATE Users SET username = :username, email = :email, role = :role WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':role' => $role,
        ':user_id' => $user_id
    ]);

    $success = "User updated successfully!";
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="add_edit_user.css">
</head>
<body>
    <h1>Admin - Edit User</h1>

    <!-- Display success or error messages -->
    <?php if (isset($success)): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

        <label>Username:</label>
        <input type="text" name="username" value="<?= $user['username'] ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= $user['email'] ?>" required><br>

        <label>Role:</label>
        <select name="role" required>
            <option value="editor" <?= ($user['role'] == 'editor') ? 'selected' : '' ?>>Editor</option>
            <option value="registered" <?= ($user['role'] == 'registered') ? 'selected' : '' ?>>Registered User</option>
            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
        </select><br>

        <button type="submit">Update User</button>
    </form>
</body>
</html>
