<?php
require_once 'db_connect.php';

// Function to check if the username already exists
function usernameExists($username, $pdo) {
    $query = "SELECT * FROM Users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $username]);
    return $stmt->rowCount() > 0;
}

// Function to check if the email already exists
function emailExists($email, $pdo) {
    $query = "SELECT * FROM Users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':email' => $email]);
    return $stmt->rowCount() > 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'registered';

    // Check if the passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (usernameExists($username, $pdo)) { // Check if the username already exists
        $error = "Username is already taken. Please choose a different one.";
    } elseif (emailExists($email, $pdo)) { // Check if the email already exists
        $error = "Email is already registered. Please use a different email.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database
        $query = "INSERT INTO Users (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashed_password,
            ':role' => $role
        ]);

        // Success message
        echo "Registration successful!";
        
        // Redirect to login page after successful registration
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register-login.css">
</head>
<body>
    <h2>Register</h2>

    <!-- Display error message if passwords do not match, username exists, or email exists -->
    <?php if (isset($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>

        <button type="submit">Register</button>
    </form>

    <!-- Button to go to login page -->
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
