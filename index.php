<?php
require_once 'db_connect.php';
session_start(); // Start session to check if user is logged in

// Initialize search, sort, and direction variables
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'title'; // Default sort by title
$direction = isset($_GET['direction']) && $_GET['direction'] == 'desc' ? 'DESC' : 'ASC'; // Default sort direction ASC

// Construct the query to fetch books, search in title, author, and publication year
$query = "SELECT book_id, title, author, publication_year 
          FROM Books 
          WHERE title LIKE :search OR author LIKE :search OR publication_year LIKE :search
          ORDER BY $sort $direction";

// Prepare the query and execute with the search term
$stmt = $pdo->prepare($query);
$stmt->execute([':search' => "%$search%"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BookShelf Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <h1>Welcome to BookShelf Hub</h1>
        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Available Books</h2>
            
            <!-- Search and Sort Form -->
            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'registered' || $_SESSION['role'] === 'admin')): ?>
            <div class="search-sort">
                <form action="" method="get" style="display: flex; align-items: center; width: 100%; gap: 20px;">
                    <input type="text" name="search" placeholder="Search books..." value="<?= htmlspecialchars($search) ?>" />
                    <select name="sort">
                        <option value="title" <?= $sort == 'title' ? 'selected' : '' ?>>Sort by Title</option>
                        <option value="author" <?= $sort == 'author' ? 'selected' : '' ?>>Sort by Author</option>
                        <option value="publication_year" <?= $sort == 'publication_year' ? 'selected' : '' ?>>Sort by Year</option>
                    </select>
                    <select name="direction">
                        <option value="asc" <?= $direction == 'ASC' ? 'selected' : '' ?>>Ascending</option>
                        <option value="desc" <?= $direction == 'DESC' ? 'selected' : '' ?>>Descending</option>
                    </select>
                    <button type="submit">Search</button>
                </form>
            </div>
            <?php endif; ?>
            
            <!-- Book List -->
            <div class="book-list">
                <?php if ($stmt->rowCount() > 0): ?>
                    <?php while ($row = $stmt->fetch()): ?>
                        <a href="view_book.php?book_id=<?= htmlspecialchars($row['book_id']) ?>" class="book-cover">
                            <h3><?= htmlspecialchars($row['title']) ?></h3>
                            <p><?= htmlspecialchars($row['author']) ?></p>
                            <p><small>(<?= htmlspecialchars($row['publication_year']) ?>)</small></p>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No results found for your search.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> BookShelf Hub. All Rights Reserved.</p>
    </footer>
</body>
</html>
