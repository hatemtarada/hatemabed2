<?php

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = intval($_POST['news_id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if ($news_id && $username && $comment) {
        $stmt = $conn->prepare("INSERT INTO comments (news_id, username, comment, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $news_id, $username, $comment);
        $stmt->execute();
    }
}

header("Location: details.php?id=$news_id");
exit;
?>
