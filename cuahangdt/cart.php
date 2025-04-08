<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối CSDL
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 🛒 **Xử lý thêm sản phẩm vào giỏ hàng**
if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);

    // Kiểm tra sản phẩm có tồn tại không
    $result = mysqli_query($connect, "SELECT * FROM products WHERE product_id = $product_id");
    if (mysqli_num_rows($result) > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++; // Tăng số lượng nếu đã có
        } else {
            $_SESSION['cart'][$product_id] = 1; // Thêm mới
        }
    }
    // Chuyển hướng để tránh reload lại nhiều lần
    header("Location: cart.php");
    exit();
}

// 🛒 **Xử lý cập nhật số lượng**
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $product_id = intval($product_id);
        $quantity = intval($quantity);
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header("Location: cart.php");
    exit();
}

// 🗑 **Xóa sản phẩm khỏi giỏ hàng**
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}

// **Lấy thông tin sản phẩm trong giỏ hàng**
$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
    $sql = "SELECT * FROM products WHERE product_id IN ($ids)";
    $result = mysqli_query($connect, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $_SESSION['cart'][$row['product_id']];
        $row['subtotal'] = $row['product_price'] * $row['quantity'];
        $total_price += $row['subtotal'];
        $cart_items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ Hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h1 class="text-center">🛒 Giỏ hàng của bạn</h1>

    <?php if (!empty($cart_items)) { ?>
        <form method="POST" action="cart.php">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo number_format($item['product_price'], 0, ',', '.'); ?> VND</td>
                            <td>
                                <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control">
                            </td>
                            <td><?php echo number_format($item['subtotal'], 0, ',', '.'); ?> VND</td>
                            <td>
                                <a href="cart.php?remove=<?php echo $item['product_id']; ?>" class="btn btn-danger">Xóa</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <h3 class="text-end">Tổng tiền: <?php echo number_format($total_price, 0, ',', '.'); ?> VND</h3>
            <div class="text-end">
                <button type="submit" name="update_cart" class="btn btn-primary">Cập nhật giỏ hàng</button>
                <a href="checkout.php" class="btn btn-success">Thanh toán</a>
            </div>
        </form>
    <?php } else { ?>
        <p class="text-center text-muted">Giỏ hàng trống!</p>
    <?php } ?>
</body>
<div class="back-to-home">
    <a href="index.html" class="home-button">🏠 Quay Lại Trang Chủ</a>
</div>
</html>
