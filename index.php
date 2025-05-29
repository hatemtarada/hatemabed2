<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>بوابة المشروع</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #f0f4f8;
      font-family: 'Tajawal', sans-serif;
    }
    .header {
      background-color: #003366;
      color: white;
      padding: 30px;
      text-align: center;
      border-bottom: 5px solid #ffc107;
    }
    .page-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      transition: transform 0.2s ease;
      padding: 30px;
    }
    .page-card:hover {
      transform: translateY(-5px);
    }
    .btn-link {
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="header">
  <h1>بوابة مشروع الأخبار</h1>
  <p class="lead">اختر الصفحة</p>
</div>

<div class="container py-5">
  <div class="row g-4 justify-content-center">
    <div class="col-md-4">
      <a href="Front_Page.php" class="btn-link">
        <div class="card page-card text-center">
          <h5>الصفحة الرئيسية</h5>
          <p class="text-muted">Front_Page.php</p>
          <span class="btn btn-primary">دخول</span>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="login.php" class="btn-link">
        <div class="card page-card text-center">
          <h5>تسجيل الدخول</h5>
          <p class="text-muted">login.php</p>
          <span class="btn btn-warning">تسجيل الدخول</span>
        </div>
      </a>
    </div>
  </div>
</div>

</body>
</html>
