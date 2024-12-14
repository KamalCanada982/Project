<?php
require_once 'db_connect.php';
session_start();

// Generate a CAPTCHA if it doesn't exist in the session
if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
}

if (!isset($_GET['book_id']) || empty($_GET['book_id'])) {
    echo "Invalid book ID.";
    exit;
}

$book_id = $_GET['book_id'];

// Fetch book details
$query = "SELECT * FROM Books WHERE book_id = :book_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':book_id' => $book_id]);
$book = $stmt->fetch();

if (!$book) {
    echo "Book not found.";
    exit;
}

// Fetch comments
$commentsQuery = "SELECT c.comment_id, c.content, c.created_at, u.username
                  FROM Comments c
                  JOIN Users u ON c.user_id = u.user_id
                  WHERE c.book_id = :book_id
                  ORDER BY c.created_at DESC";
$commentsStmt = $pdo->prepare($commentsQuery);
$commentsStmt->execute([':book_id' => $book_id]);
$comments = $commentsStmt->fetchAll();

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$user_id = $_SESSION['user_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f9f9f9; color: #333; padding: 20px; }
        header { text-align: center; margin-bottom: 30px; }
        .book-details { max-width: 800px; margin: 0 auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .comments-section, .add-comment { max-width: 800px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .comment { border-bottom: 1px solid #ddd; padding: 10px 0; }
        textarea, input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px; }
        button { padding: 10px 15px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .captcha { font-size: 1.5rem; font-weight: bold; color: #007bff; text-align: center; background: #f0f8ff; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($book['title']) ?></h1>
    </header>

    <div class="book-details">
    <?php if (!empty($book['image'])): ?>
        <p><strong>Book Image:</strong></p>
        <img src="uploads/<?= htmlspecialchars($book['image']) ?>" alt="Image of <?= htmlspecialchars($book['title']) ?>" style="max-width: 100%; height: auto;">
    <?php endif; ?>
        <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
        <p><strong>Publication Year:</strong> <?= htmlspecialchars($book['publication_year']) ?></p>
        <p><strong>Price:</strong> $<?= htmlspecialchars($book['price']) ?></p>
        <a href="index.php">Back to Book List</a>
    </div>

    <div class="comments-section">
        <h2>Comments</h2>
        <?php if ($comments): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?= htmlspecialchars($comment['username']) ?>:</strong></p>
                    <p><?= htmlspecialchars($comment['content']) ?></p>
                    <p><em>Posted on <?= $comment['created_at'] ?></em></p>
                    <?php if ($is_admin): ?>
                        <form method="POST" action="delete_comment.php" style="display:inline;">
                            <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                            <button type="submit" style="color:white; cursor:pointer;">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>
    </div>

    <?php if ($user_id): ?>
        <div class="add-comment">
            <h3>Add a Comment</h3>
            <form method="POST" action="add_comment.php">
                <input type="hidden" name="book_id" value="<?= $book_id ?>">
                <textarea name="content" required placeholder="Write your comment here..." rows="4"></textarea>
                <div class="captcha"><?= $_SESSION['captcha'] ?></div>
                <input type="text" name="captcha" required placeholder="Enter CAPTCHA">
                <button type="submit">Post Comment</button>
            </form>
        </div>
    <?php else: ?>
        <p>You must <a href="login.php">log in</a> to post comments.</p>
    <?php endif; ?>
</body>
</html>
