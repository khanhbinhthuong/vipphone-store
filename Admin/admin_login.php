<!-- AdminLTE -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<?php
session_start();
require("config.php"); // Kết nối database

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (!empty($username) && !empty($password)) {
        // Truy vấn lấy thông tin user có username và role = 'admin'
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? AND role = 'admin'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            
            // Kiểm tra mật khẩu có đúng không
            if (password_verify($password, $admin['password']) || md5($password) === $admin['password']) {
                $_SESSION['admin'] = $admin['username'];
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $error = "⚠️ Mật khẩu không đúng!";
            }
        } else {
            $error = "⚠️ Tài khoản không tồn tại hoặc không phải admin!";
        }
        $stmt->close();
    } else {
        $error = "⚠️ Vui lòng nhập đầy đủ thông tin!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Đăng nhập</h2>
        <?php if (!empty($error)) : ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>
</html>
