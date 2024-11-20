<?php
require_once 'db_connect.php';

$query = "SELECT b.title, u.username, r.rating, r.comment 
          FROM Reviews r
          JOIN Books b ON r.book_id = b.book_id
          JOIN Users u ON r.user_id = u.user_id";
$stmt = $pdo->query($query);

echo "<h1>Book Reviews</h1>";
while ($row = $stmt->fetch()) {
    echo "<h3>{$row['title']} (Rated: {$row['rating']}/5)</h3>";
    echo "<p><strong>By:</strong> {$row['username']}</p>";
    echo "<p><strong>Comment:</strong> {$row['comment']}</p>";
}
?>
