<!-- AdminLTE -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<?php
require("config.php");
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$result = $conn->query("SELECT * FROM users WHERE role != 'admin'");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Người dùng</title>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Thanh điều hướng -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="admin_dashboard.php">
                    <i class="fa-solid fa-users"></i> Admin Dashboard
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link text-light" href="admin_dashboard.php">🏠 Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="manage_users.php">👥 Người dùng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="logout.php">🚪 Đăng xuất</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Nội dung chính -->
        <div class="container mt-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fa-solid fa-user"></i> Danh sách Người dùng</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['username']; ?></td>
                                    <td>
                                        <a href="delete_user.php?id=<?= $row['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Xác nhận khóa tài khoản?');">
                                            🔒 Khóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="admin_dashboard.php" class="btn btn-secondary mt-3">🔙 Quay lại</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
