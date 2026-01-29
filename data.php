<?php
// File này sẽ nhận category_id và trả về JSON để Javascript xử lý mà không cần load lại trang.
header('Content-Type: application/json');
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'korean_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die(json_encode(['error' => 'Kết nối thất bại']));

$type = $_GET['type'] ?? 'categories';

if ($type === 'categories') {
    $result = $conn->query("SELECT * FROM categories");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
} elseif ($type === 'vocab' && isset($_GET['cat_id'])) {
    $cat_id = (int)$_GET['cat_id'];
    $stmt = $conn->prepare("SELECT ko, vi, img FROM vocabulary WHERE category_id = ?");
    $stmt->bind_param("i", $cat_id);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
}
?>