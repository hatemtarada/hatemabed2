<?php
require_once 'config/db.php';

$sql = "SELECT * FROM news WHERE status = 'approved' ORDER BY dateposted DESC";
$result = $conn->query($sql);

$cats_sql = "SELECT * FROM category";
$cats_result = $conn->query($cats_sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الصفحة الرئيسية - أخبار</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #f2f4f8;
      font-family: 'Tajawal', sans-serif;
    }
    .navbar {
      background-color: #003366;
    }
    .navbar .nav-link, .navbar .navbar-brand {
      color: white;
    }
    .news-card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      margin-bottom: 25px;
      overflow: hidden;
    }
    .news-card img {
      height: 200px;
      object-fit: cover;
      width: 100%;
    }
    .news-content {
      padding: 20px;
    }
    .news-content h5 {
      color: #003366;
    }
    .category-section {
      margin-top: 30px;
    }
    .category-section h2 {
      background-color: #003366;
      color: white;
      padding: 10px;
      border-radius: 5px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">أخبار العالم</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#top-news">الرئيسية</a></li>
        <?php
        if ($cats_result->num_rows > 0) {
          mysqli_data_seek($cats_result, 0); // إعادة المؤشر للبداية
          while ($cat = $cats_result->fetch_assoc()) {
            echo '<li class="nav-item"><a class="nav-link" href="#cat_' . $cat['id'] . '">' . $cat['name'] . '</a></li>';
          }
        }
        ?>
      </ul>
      <a href="login.php" class="btn btn-light ms-2">تسجيل الدخول</a>
    </div>
  </div>
</nav>

<div class="container mt-4" id="top-news">
  <div class="row">
    <?php 
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
    ?>
      <div class="col-md-4">
        <div class="news-card">
          <img src="Uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
          <div class="news-content">
            <h5><?php echo $row['title']; ?></h5>
            <p><?php echo substr($row['body'], 0, 100) . '...'; ?></p>
            <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">المزيد</a>
          </div>
        </div>
      </div>
    <?php }} ?>
  </div>

  <?php
  if ($cats_result->num_rows > 0) {
    mysqli_data_seek($cats_result, 0); 
    while ($cat = $cats_result->fetch_assoc()) {
      $cat_id = $cat['id'];
      $cat_name = $cat['name'];

      $news_sql = "SELECT * FROM news WHERE category_id = $cat_id AND status = 'approved' ORDER BY dateposted DESC LIMIT 4";
      $news_result = $conn->query($news_sql);

      if ($news_result->num_rows > 0) {
  ?>
  <div class="category-section" id="cat_<?php echo $cat_id; ?>">
    <h2><?php echo $cat_name; ?></h2>
    <div class="row">
    <?php
      while ($row = $news_result->fetch_assoc()) {
    ?>
      <div class="col-md-4">
        <div class="news-card">
          <img src="Uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
          <div class="news-content">
            <h5><?php echo $row['title']; ?></h5>
            <p><?php echo substr($row['body'], 0, 100) . '...'; ?></p>
            <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">قراءة المزيد</a>
          </div>
        </div>
      </div>
    <?php } ?>
    </div>
  </div>
  <?php }}} ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
