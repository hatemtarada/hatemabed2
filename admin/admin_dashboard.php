<?php
require_once("../config/db.php");
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT COUNT(*) AS total_users FROM user";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_users = $row['total_users'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f4f6f9;
        }
        .sidebar {
            background-color: #34495e;
            height: 100vh;
            position: fixed;
            width: 250px;
            top: 0;
            right: 0;
            padding-top: 40px;
            color: white;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 15px 25px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #2c3e50;
        }
        .main-content {
            margin-right: 260px;
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(to left, #27ae60, #2ecc71);
            color: white;
            font-weight: bold;
        }
        .btn {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">لوحة التحكم</h4>
        <a href="admin_dashboard.php">الصفحة الرئيسية</a>
        <a href="manage_users.php">إدارة المستخدمين</a>
        <a href="../Front_Page.php">تسجيل الخروج</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2 class="mb-4">مرحبًا بك في لوحة تحكم الإدارة</h2>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">عدد المستخدمين</div>
                        <div class="card-body">
                            <h3><?php echo $total_users; ?> مستخدم</h3>
                            <p class="text-muted">إدارة جميع المستخدمين المسجلين في النظام</p>
                            <a href="manage_users.php" class="btn btn-success">عرض المستخدمين</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
