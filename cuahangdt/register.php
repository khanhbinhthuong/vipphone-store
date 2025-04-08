<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "se07102_sdlc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Kết nối database thất bại: " . $conn->connect_error]));
}

// Kiểm tra xem PHP có nhận được POST request không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    file_put_contents("debug_post.txt", json_encode($_POST)); // Ghi log dữ liệu vào file để kiểm tra

    // Kiểm tra dữ liệu đầu vào
    if (!isset($_POST["username"], $_POST["email"], $_POST["password"])) {
        die(json_encode(["status" => "error", "message" => "Dữ liệu gửi lên không hợp lệ!"]));
    }

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($email) || empty($password)) {
        die(json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ thông tin!"]));
    }

    // Kiểm tra username hoặc email đã tồn tại chưa
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        die(json_encode(["status" => "error", "message" => "Lỗi chuẩn bị truy vấn: " . $conn->error]));
    }
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        die(json_encode(["status" => "error", "message" => "Tên đăng nhập hoặc email đã tồn tại!"]));
    }
    $stmt->close();

    // Hash password trước khi lưu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Thêm user vào database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at, role) VALUES (?, ?, ?, NOW(), 'user')");
    if (!$stmt) {
        die(json_encode(["status" => "error", "message" => "Lỗi chuẩn bị truy vấn: " . $conn->error]));
    }
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Đăng ký thành công!"]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Lỗi SQL: " . $stmt->error,
            "query" => $stmt->error_list // Hiển thị danh sách lỗi
        ]);
    }

    $stmt->close();
}

// Đóng kết nối
$conn->close();
?>
