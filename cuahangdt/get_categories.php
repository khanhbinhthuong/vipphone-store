<?php
// Thiết lập header JSON
header('Content-Type: application/json; charset=UTF-8');

// Kết nối đến database
$conn = new mysqli("localhost", "root", "", "se07102_sdlc");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]));
}

$conn->set_charset("utf8");

// Truy vấn danh mục
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = [
            "category_id" => $row["category_id"],
            "category_name" => $row["category_name"]
        ];
    }
}

// Trả về JSON
echo json_encode($categories, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
