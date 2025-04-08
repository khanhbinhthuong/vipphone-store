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

$result = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Đơn hàng</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Thanh điều hướng -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="admin_dashboard.php">
                    <i class="fa-solid fa-box"></i> Admin Dashboard
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
                            <a class="nav-link text-light" href="manage_orders.php">📦 Đơn hàng</a>
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
                    <h3 class="mb-0"><i class="fa-solid fa-list"></i> Danh sách đơn hàng</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Khách hàng</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['order_id']; ?></td>
                                    <td><?= $row['customer_name']; ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= $row['status'] == 'pending' ? 'bg-warning' : 
                                                ($row['status'] == 'shipped' ? 'bg-info' : 
                                                ($row['status'] == 'delivered' ? 'bg-success' : 'bg-danger')) ?>">
                                            <?= ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit_order.php?id=<?= $row['order_id']; ?>" class="btn btn-sm btn-primary">
                                            ✏️ Cập nhật
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
