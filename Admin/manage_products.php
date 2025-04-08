
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

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'admin_sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <h2>Danh sách sản phẩm</h2>
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Hành động</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['product_id']; ?></td>
                            <td><?= $row['product_name']; ?></td>
                            <td><?= $row['product_price']; ?></td>
                            <td>
    <a href="edit_product.php?id=<?= htmlspecialchars($row['product_id']); ?>" class="btn btn-warning">Sửa</a>
    <a href="delete_product.php?id=<?= htmlspecialchars($row['product_id']); ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
</td>

                        </tr>
                    <?php endwhile; ?>
                </table>
                <a href="admin_dashboard.php" class="btn btn-secondary">Quay lại</a>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        
    </footer>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>
