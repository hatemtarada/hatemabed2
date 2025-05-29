<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'author') {
    header("Location: ../login.php");
    exit;
}

$author_id = $_SESSION['user_id'];
$categories_result = $conn->query("SELECT id, name FROM category");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $category_id = $_POST['category_id'];
    $image_name = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($image_tmp, '../uploads/' . $image_name);
    }

    $stmt = $conn->prepare("INSERT INTO news (title, body, image, dateposted, category_id, author_id, status) VALUES (?, ?, ?, NOW(), ?, ?, 'pending')");
    $stmt->bind_param("sssii", $title, $body, $image_name, $category_id, $author_id);

    if ($stmt->execute()) {
        $success_message = "تمت إضافة الخبر بنجاح! بانتظار الموافقة.";
    } else {
        $error_message = "حدث خطأ: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة خبر جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f7f9fc;
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
            display: block;
            padding: 15px 25px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .main-content {
            margin-right: 270px;
            padding: 30px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">لوحة المؤلف</h4>
        <a href="author_dashboard.php">لوحة التحكم</a>
        <a href="add_news.php">إضافة خبر جديد</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2 class="mb-4">إضافة خبر جديد</h2>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success text-center"> <?= $success_message ?> </div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger text-center"> <?= $error_message ?> </div>
            <?php endif; ?>

            <div class="form-container">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">عنوان الخبر</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">محتوى الخبر</label>
                        <textarea name="body" class="form-control" rows="6" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التصنيف</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- اختر التصنيف --</option>
                            <?php while($row = $categories_result->fetch_assoc()): ?>
                                <option value="<?= $row['id']; ?>"> <?= htmlspecialchars($row['name']); ?> </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">صورة الخبر</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">نشر الخبر</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
