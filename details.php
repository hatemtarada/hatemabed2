<?php

require_once 'config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: Front_Page.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT news.*, category.name AS category_name FROM news 
        LEFT JOIN category ON news.category_id = category.id
        WHERE news.id = $id AND news.status = 'approved'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "لا يوجد خبر بهذا الرقم.";
    exit;
}

$news = $result->fetch_assoc();
$comments_sql = "SELECT * FROM comments WHERE news_id = $id ORDER BY created_at DESC";
$comments_result = $conn->query($comments_sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($news['title']) ?> - أخبار العالم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tajawal', sans-serif;
        }
        .article-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin: 40px auto;
            max-width: 800px;
        }
        .article-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .article-meta {
            color: #6c757d;
            margin-bottom: 15px;
        }
        .comment-box {
            background-color: #eef1f4;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="article-container">
            <h2><?= htmlspecialchars($news['title']) ?></h2>
            <div class="article-meta">
                <span><?= date("d F Y", strtotime($news['dateposted'])) ?></span> |
                <span><?= htmlspecialchars($news['category_name']) ?></span>
            </div>
            <img src="Uploads/<?= htmlspecialchars($news['image']) ?>" alt="صورة الخبر" class="article-image">
            <p><?= nl2br(htmlspecialchars($news['body'])) ?></p>

            <hr>
            <h4>التعليقات</h4>
            <?php if ($comments_result->num_rows > 0): ?>
                <?php while($comment = $comments_result->fetch_assoc()): ?>
                    <div class="comment-box">
                        <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">لا توجد تعليقات بعد.</p>
            <?php endif; ?>

            <hr>
            <h5>أضف تعليقًا</h5>
            <form action="add_comment.php" method="post">
                <input type="hidden" name="news_id" value="<?= $id ?>">
                <div class="mb-3">
                    <label class="form-label">اسم المستخدم</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">التعليق</label>
                    <textarea name="comment" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">إرسال</button>
            </form>

            <div class="mt-4">
                <button onclick="vote(<?= $id ?>, 'like')" class="btn btn-outline-success me-2">
                    👍 إعجاب <span id="likes-count"><?= $news['likes'] ?? 0 ?></span>
                </button>
                <button onclick="vote(<?= $id ?>, 'dislike')" class="btn btn-outline-danger">
                    👎 عدم إعجاب <span id="dislikes-count"><?= $news['dislikes'] ?? 0 ?></span>
                </button>
            </div>
        </div>
    </div>

<script>
function vote(newsId, type) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "vote.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const res = JSON.parse(xhr.responseText);
            document.getElementById("likes-count").textContent = res.likes;
            document.getElementById("dislikes-count").textContent = res.dislikes;
        }
    };
    xhr.send(`news_id=${newsId}&type=${type}`);
}
</script>
</body>
</html>
