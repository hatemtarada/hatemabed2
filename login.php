<?php
session_start();
include 'config/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $storedPassword = $user['password'];

        $loginSuccess = false;

        if (password_verify($password, $storedPassword)) {
            $loginSuccess = true;
        } elseif ($password === $storedPassword) {
            $loginSuccess = true;

            $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $newHashedPassword, $user['id']);
            $updateStmt->execute();
            $updateStmt->close();
        }

        if ($loginSuccess) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: Admin/admin_dashboard.php");
            } elseif ($user['role'] === 'author') {
                header("Location: author/author_dashboard.php");
            } elseif ($user['role'] === 'editor') {
                header("Location: editor/editor_dashboard.php");
            }
            exit;
        }
    }

    $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        .box {
            margin: 10px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 320px;
            padding: 40px 30px;
            background-color: rgba(0, 0, 0, 0.75);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
            border-radius: 16px;
            text-align: center;
        }

        .box h2 {
            font-size: 24px;
            color: aliceblue;
            margin-bottom: 20px;
        }

        .box input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            color: white;
            background: transparent;
            border: none;
            border-bottom: 1px solid rgb(170, 104, 6);
            outline: none;
        }

        .box label {
            display: block;
            color: aliceblue;
            font-size: 16px;
            margin-bottom: 5px;
            text-align: right;
        }

        .box button {
            color: white;
            background-color: rgb(170, 104, 6);
            border: 0;
            border-radius: 7px;
            margin: 5px;
            padding: 10px 20px;
            width: 100%;
        }

        .box button:hover {
            background-color: black;
            color: chocolate;
            box-shadow: 1px 1px 5px black;
        }

        .box p {
            font-size: 12px;
            text-align: center;
            color: rgb(170, 104, 6);
            margin-top: 15px;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="box">
        <h2>تسجيل الدخول</h2>

        <?php if ($error): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="email">البريد الإلكتروني</label>
            <input type="text" name="email" id="email" required>

            <label for="password">كلمة المرور</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">الدخول</button>
        </form>

        <p>جميع الحقوق محفوظة 2022-2025، جــــامعــــــة البوليتكنك ©</p>
    </div>

</body>

</html>
