<?php
session_start();
include '../config/db.php';

if ($_SESSION['user_role'] != 'author') {
    header("Location: login.php");
    exit();
}

$author_id = $_SESSION['user_id'];
$sql = "SELECT news.*, category.name AS category_name FROM news INNER JOIN category ON news.category_id = category.id WHERE author_id = $author_id ORDER BY dateposted DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المؤلف</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
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
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .badge {
            font-size: 0.9rem;
            padding: 0.5em 0.75em;
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
            <h2 class="mb-4">أخباري المنشورة</h2>
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-6">
                        <div class="card p-3">
                            <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="text-muted mb-1">التصنيف: <?php echo htmlspecialchars($row['category_name']); ?></p>
                            <p class="text-muted mb-2">بتاريخ: <?php echo date("Y-m-d", strtotime($row['dateposted'])); ?></p>
                            <p class="mb-2">الحالة: 
                                <?php if ($row['status'] == 'approved'): ?>
                                    <span class="badge bg-success">مقبول</span>
                                <?php elseif ($row['status'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark">قيد الانتظار</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">مرفوض</span>
                                <?php endif; ?>
                            </p>
                            <div>
                                <a href="edit_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">تعديل</a>
                                <a href="delete_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>
