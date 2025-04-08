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

// Xử lý thêm sản phẩm
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $quantity = $_POST['quantity'];
    $product_description = $_POST['product_description'];

    // Xử lý file ảnh
    $product_img = $_FILES['product_img']['name'];
    $product_img_tmp = $_FILES['product_img']['tmp_name'];
    $upload_dir = "images/" . basename($product_img);

    if (move_uploaded_file($product_img_tmp, $upload_dir)) {
        $sql = "INSERT INTO products (product_name, product_price, quantity, product_img, product_description) 
                VALUES ('$product_name', '$product_price', '$quantity', '$product_img', '$product_description')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Thêm sản phẩm thành công'); window.location='manage_products.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi thêm sản phẩm');</script>";
        }
    } else {
        echo "<script>alert('Lỗi khi tải ảnh');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm Sản Phẩm</title>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="container mt-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3><i class="fa fa-plus"></i> Thêm Sản Phẩm</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" name="product_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá sản phẩm</label>
                            <input type="number" name="product_price" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số lượng</label>
                            <input type="number" name="quantity" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ảnh sản phẩm</label>
                            <input type="file" name="product_img" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả sản phẩm</label>
                            <textarea name="product_description" class="form-control" rows="3" required></textarea>
                        </div>

                        <button type="submit" name="add_product" class="btn btn-success">Thêm sản phẩm</button>
                        <a href="admin_dashboard.php" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
