<?php
session_start();
header("Content-Type: application/json");

// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "se07102_sdlc";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Kết nối thất bại!"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ thông tin!"]);
        exit;
    }

    // Kiểm tra user trong database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION['username'] = $username; // Lưu phiên đăng nhập

            // ✅ Trả về URL cần chuyển hướng (client sẽ xử lý)
            echo json_encode([
                "status" => "success",
                "message" => "Đăng nhập thành công!",
                "redirect" => "home.php"
            ]);
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "Mật khẩu không đúng!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Tên đăng nhập không tồn tại!"]);
    }
}

$conn->close();
?>
