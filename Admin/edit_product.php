<?php
require("config.php");
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Kiểm tra ID hợp lệ
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID không hợp lệ.");
}

$id = intval($_GET['id']);

// Lấy dữ liệu sản phẩm từ database
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Sản phẩm không tồn tại.");
}

// Xử lý cập nhật sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];

    $update = $conn->prepare("UPDATE products SET product_name = ?, product_price = ? WHERE product_id = ?");
    $update->bind_param("sdi", $name, $price, $id);

    if ($update->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location='manage_products.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật sản phẩm!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa sản phẩm</title>
</head>
<body>
    <h2>Chỉnh sửa sản phẩm</h2>
    <form method="POST">
        <label>Tên sản phẩm:</label>
        <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']); ?>" required>
        <label>Giá:</label>
        <input type="number" name="product_price" value="<?= $product['product_price']; ?>" required>
        <button type="submit">Lưu</button>
    </form>
    <a href="manage_products.php">Quay lại</a>
</body>
</html>
