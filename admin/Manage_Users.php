<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../Front_Page.php");
    exit();
}

if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    $sql = "DELETE FROM user WHERE id = $userId";
    mysqli_query($conn, $sql);
    header("Location: manage_users.php");
    exit();
}

$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المستخدمين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #ecf0f1;
        }
        .sidebar {
            background-color: #2c3e50;
            color: white;
            position: fixed;
            width: 250px;
            height: 100vh;
            top: 0;
            right: 0;
            padding: 30px 15px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin-bottom: 10px;
            border-radius: 5px;
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
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .btn-custom {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">لوحة التحكم</h4>
        <a href="admin_dashboard.php">الرئيسية</a>
        <a href="manage_users.php">إدارة المستخدمين</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2 class="mb-4">إدارة المستخدمين</h2>

            <div class="table-container">
                <div class="d-flex justify-content-end mb-3">
                    <a href="add_users.php" class="btn btn-success btn-custom">إضافة مستخدم جديد</a>
                </div>
                <table class="table table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php
                                echo match ($user['role']) {
                                    'admin' => 'مدير',
                                    'editor' => 'محرر',
                                    'author' => 'كاتب',
                                    default => 'غير معروف'
                                };
                            ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">تعديل</a>
                                <a href="manage_users.php?delete=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
