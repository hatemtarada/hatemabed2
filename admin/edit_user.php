<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../Front_Page.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $sql = "SELECT * FROM user WHERE id = $userId";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
} else {
    header("Location: manage_users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($password == '') {
        $password = $user['password'];
    }

    $sql = "UPDATE user SET name = '$name', email = '$email', password = '$password', role = '$role' WHERE id = $userId";
    mysqli_query($conn, $sql);

    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل المستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }
        .card {
            max-width: 600px;
            margin: 50px auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 15px;
        }
        .card-header {
            background-color: #f39c12;
            color: white;
            font-size: 1.25rem;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }
        .btn-primary {
            background-color: #f39c12;
            border-color: #f39c12;
        }
        .btn-primary:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header text-center">تعديل المستخدم</div>
        <div class="card-body">
            <form method="POST" action="edit_user.php?id=<?php echo $user['id']; ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور (اختياري)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">الدور</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>مدير</option>
                        <option value="editor" <?php if ($user['role'] == 'editor') echo 'selected'; ?>>محرر</option>
                        <option value="author" <?php if ($user['role'] == 'author') echo 'selected'; ?>>كاتب</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">تحديث المستخدم</button>
            </form>
        </div>
    </div>
</body>
</html>
