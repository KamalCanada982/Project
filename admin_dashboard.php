<?php
// Start the session and include the necessary connection file
require_once 'db_connect.php';
session_start();

// Restrict access to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize search and sort variables for books and users
$bookSearch = isset($_GET['book_search']) ? $_GET['book_search'] : '';
$bookSort = isset($_GET['book_sort']) ? $_GET['book_sort'] : 'title';
$bookDirection = isset($_GET['book_direction']) && $_GET['book_direction'] == 'desc' ? 'DESC' : 'ASC';

$userSearch = isset($_GET['user_search']) ? $_GET['user_search'] : '';
$userSort = isset($_GET['user_sort']) ? $_GET['user_sort'] : 'username';
$userDirection = isset($_GET['user_direction']) && $_GET['user_direction'] == 'desc' ? 'DESC' : 'ASC';

// Query to fetch books with search and sort
$bookQuery = "SELECT * FROM Books WHERE title LIKE :book_search OR author LIKE :book_search ORDER BY $bookSort $bookDirection";
$bookStmt = $pdo->prepare($bookQuery);
$bookStmt->execute([':book_search' => "%$bookSearch%"]);

// Query to fetch users with search and sort
$userQuery = "SELECT * FROM Users WHERE username LIKE :user_search ORDER BY $userSort $userDirection";
$userStmt = $pdo->prepare($userQuery);
$userStmt->execute([':user_search' => "%$userSearch%"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

<header>
    <h1>Admin Dashboard</h1>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
    <nav><a href="logout.php" class="logout-btn">Logout</a></nav>
</header>

<div class="container">

    <!-- Manage Books Section -->
    <section class="manage-section">
        <h3>Manage Books</h3>

        <!-- Search and Sort Form for Books -->
        <div class="search-sort">
            <form action="" method="get" style="display: flex; gap: 20px;">
                <input type="text" name="book_search" placeholder="Search books..." value="<?= htmlspecialchars($bookSearch) ?>" />
                <select name="book_sort">
                    <option value="title" <?= $bookSort == 'title' ? 'selected' : '' ?>>Sort by Title</option>
                    <option value="author" <?= $bookSort == 'author' ? 'selected' : '' ?>>Sort by Author</option>
                    <option value="publication_year" <?= $bookSort == 'publication_year' ? 'selected' : '' ?>>Sort by Year</option>
                </select>
                <select name="book_direction">
                    <option value="asc" <?= $bookDirection == 'ASC' ? 'selected' : '' ?>>Ascending</option>
                    <option value="desc" <?= $bookDirection == 'DESC' ? 'selected' : '' ?>>Descending</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Add New Book Button -->
        <div class="add-links">
            <a href="add_book.php" class="add-link">Add New Book</a>
        </div>

        <!-- Books Table -->
<table class="admin-table">
    <thead>
        <tr><th>Title</th><th>Author</th><th>Year</th><th>Price</th><th>Actions</th></tr>
    </thead>
    <tbody>
        <?php while ($book = $bookStmt->fetch()): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['publication_year']) ?></td>
                <td><?= htmlspecialchars($book['price']) ?></td>
                <td>
                    <a href="view_book.php?book_id=<?= $book['book_id'] ?>" class="action-link">View</a> |
                    <a href="edit_book.php?book_id=<?= $book['book_id'] ?>" class="action-link">Edit</a> | 
                    <a href="delete_book.php?book_id=<?= $book['book_id'] ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

    </section>

    <!-- Manage Users Section -->
    <section class="manage-section">
        <h3>Manage Users</h3>

        <!-- Search and Sort Form for Users -->
        <div class="search-sort">
            <form action="" method="get" style="display: flex; gap: 20px;">
                <input type="text" name="user_search" placeholder="Search users..." value="<?= htmlspecialchars($userSearch) ?>" />
                <select name="user_sort">
                    <option value="username" <?= $userSort == 'username' ? 'selected' : '' ?>>Sort by Username</option>
                    <option value="role" <?= $userSort == 'role' ? 'selected' : '' ?>>Sort by Role</option>
                </select>
                <select name="user_direction">
                    <option value="asc" <?= $userDirection == 'ASC' ? 'selected' : '' ?>>Ascending</option>
                    <option value="desc" <?= $userDirection == 'DESC' ? 'selected' : '' ?>>Descending</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Add New User Button -->
        <div class="add-links">
            <a href="add_user.php" class="add-link">Add New User</a>
        </div>

        <!-- Users Table -->
        <table class="admin-table">
            <thead>
                <tr><th>Username</th><th>Role</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while ($user = $userStmt->fetch()): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <a href="edit_user.php?user_id=<?= $user['user_id'] ?>" class="action-link">Edit</a> | 
                            <a href="delete_user.php?user_id=<?= $user['user_id'] ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

</div>

</body>
</html>
