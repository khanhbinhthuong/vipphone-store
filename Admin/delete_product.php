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

// Kiểm tra sản phẩm có tồn tại không
$check = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$check->bind_param("i", $id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows === 0) {
    die("Sản phẩm không tồn tại.");
}

// Xóa sản phẩm
$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Xóa sản phẩm thành công!'); window.location='manage_products.php';</script>";
} else {
    echo "<script>alert('Lỗi khi xóa sản phẩm!');</script>";
}
?>
