<?php 
$conn = new mysqli("localhost", "root", "", "project_part_tow_web");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $sql = "INSERT INTO user (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    mysqli_query($conn, $sql);

    echo "<script>alert('تم إضافة المستخدم بنجاح');</script>";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة مستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Tajawal', sans-serif;
        }
        .card {
            max-width: 600px;
            margin: 50px auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(to left, #3498db, #2ecc71);
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            padding: 20px;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header text-center">إضافة مستخدم جديد</div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">الاسم</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">الدور</label>
                    <select name="role" class="form-select" required>
                        <option value="admin">مدير</option>
                        <option value="editor">محرر</option>
                        <option value="author">مؤلف</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">إضافة</button>
            </form>
        </div>
    </div>
</body>
</html>
