<?php

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = intval($_POST['news_id']);
    $type = $_POST['type'];

    if (!in_array($type, ['like', 'dislike'])) {
        http_response_code(400);
        echo json_encode(["error" => "نوع تصويت غير صالح"]);
        exit;
    }

    $column = $type === 'like' ? 'likes' : 'dislikes';

    $update_sql = "UPDATE news SET $column = $column + 1 WHERE id = $news_id";
    if ($conn->query($update_sql)) {
        $count_sql = "SELECT likes, dislikes FROM news WHERE id = $news_id";
        $result = $conn->query($count_sql);
        if ($result && $row = $result->fetch_assoc()) {
            echo json_encode([
                "likes" => $row['likes'],
                "dislikes" => $row['dislikes']
            ]);
        } else {
            echo json_encode(["likes" => 0, "dislikes" => 0]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["error" => "فشل في تحديث قاعدة البيانات"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "الطريقة غير مسموح بها"]);
}
?>
