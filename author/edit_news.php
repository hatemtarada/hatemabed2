<?php
session_start();
include '../config/db.php';

if ($_SESSION['user_role'] != 'author') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "لا يوجد معرف خبر لتعديله.";
    exit();
}

$news_id = intval($_GET['id']);
$author_id = $_SESSION['user_id'];
$message = "";

$query = "SELECT * FROM news WHERE id = ? AND author_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $news_id, $author_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "الخبر غير موجود أو ليس لديك صلاحية لتعديله.";
    exit();
}

$news = $result->fetch_assoc();
$cat_result = $conn->query("SELECT * FROM category");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $category_id = $_POST['category_id'];

    $update_query = "UPDATE news SET title = ?, body = ?, category_id = ? WHERE id = ? AND author_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssiii", $title, $body, $category_id, $news_id, $author_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>تم تحديث الخبر بنجاح.</div>";
        $news['title'] = $title;
        $news['body'] = $body;
        $news['category_id'] = $category_id;
    } else {
        $message = "<div class='alert alert-danger'>حدث خطأ أثناء التحديث.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل الخبر</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f4f6f9;
        }
        .sidebar {
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            top: 0;
            right: 0;
            padding-top: 30px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px 25px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .main-content {
            margin-right: 270px;
            padding: 30px;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            max-width: 700px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">لوحة التحكم</h4>
        <a href="author_dashboard.php">لوحة تحكم المؤلف</a>
        <a href="add_news.php">إضافة خبر جديد</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2 class="mb-4">تعديل الخبر</h2>
            <?= $message ?>
            <div class="form-container">
                <form method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">عنوان الخبر</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($news['title']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">التصنيف</label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <?php while ($cat = $cat_result->fetch_assoc()): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $news['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">نص الخبر</label>
                        <textarea id="body" name="body" class="form-control" rows="6" required><?= htmlspecialchars($news['body']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    <a href="author_dashboard.php" class="btn btn-secondary">إلغاء</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
