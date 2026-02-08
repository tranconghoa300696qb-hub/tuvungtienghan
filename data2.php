<?php
header('Content-Type: application/json; charset=utf-8');
$conn = new mysqli("localhost", "root", "", "korean_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối database thất bại"]));
}

$type = $_GET['type'] ?? '';

if ($type == 'categories') {
    $parent_id = intval($_GET['parent_id'] ?? 0);
    // Lấy danh mục
    $sql = "SELECT id, title FROM categories WHERE parent_id = $parent_id";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
} 

elseif ($type == 'vocab') {
    $cat_id = intval($_GET['cat_id'] ?? 0);
    // SQL của bạn dùng cột ko, vi, img nên ta SELECT đúng như vậy
    $sql = "SELECT ko, vi, img FROM vocabulary WHERE category_id = $cat_id";
    $result = $conn->query($sql);
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}
?>