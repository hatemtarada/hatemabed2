<?php

session_start();
include '../config/db.php';

if ($_SESSION['user_role'] != 'editor') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approved') {
        $conn->query("UPDATE news SET status='approved' WHERE id=$id");
    } elseif ($action === 'deny') {
        $conn->query("UPDATE news SET status='denied' WHERE id=$id");
    } elseif ($action === 'delete') {
        $conn->query("DELETE FROM news WHERE id=$id");
    }

    header("Location: editor_dashboard.php");
    exit();
}

$sql = "SELECT news.*, category.name AS category_name FROM news LEFT JOIN category ON news.category_id = category.id ORDER BY dateposted DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المحرر</title>
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
        .table-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .badge {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">لوحة المحرر</h4>
        <a href="editor_dashboard.php">إدارة الأخبار</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2 class="mb-4">إدارة جميع الأخبار</h2>
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>العنوان</th>
                            <th>التصنيف</th>
                            <th>تاريخ النشر</th>
                            <th>الحالة</th>
                            <th>خيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']); ?></td>
                                <td><?= htmlspecialchars($row['category_name']); ?></td>
                                <td><?= date("Y-m-d", strtotime($row['dateposted'])); ?></td>
                                <td>
                                    <?php
                                        echo match ($row['status']) {
                                            'approved' => '<span class="badge bg-success">مقبول</span>',
                                            'denied'   => '<span class="badge bg-danger">مرفوض</span>',
                                            default    => '<span class="badge bg-warning text-dark">معلق</span>'
                                        };
                                    ?>
                                </td>
                                <td>
                                    <a href="?action=approved&id=<?= $row['id']; ?>" class="btn btn-sm btn-success">موافقة</a>
                                    <a href="?action=deny&id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">رفض</a>
                                    <a href="?action=delete&id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
