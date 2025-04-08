<?php
// Thiết lập header để trả về JSON và hỗ trợ tiếng Việt
header('Content-Type: application/json; charset=UTF-8');

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "se07102_sdlc");

// Kiểm tra kết nối
if ($conn->connect_error) {
    // Trả về lỗi JSON thay vì die() để client có thể xử lý
    echo json_encode(["error" => "Kết nối thất bại: " . $conn->connect_error]);
    exit(); // Dừng thực thi script
}

// Chỉnh UTF-8 để hỗ trợ tiếng Việt
$conn->set_charset("utf8");

// *** THAY ĐỔI: Lấy tham số search và category từ GET ***
$search = isset($_GET['search']) ? $_GET['search'] : null;
$category = isset($_GET['category']) ? $_GET['category'] : null;

// Truy vấn lấy danh sách sản phẩm
$sql = "SELECT product_id, product_name, product_price, product_img FROM products"; // Chỉ lấy các cột cần thiết
$where = [];
$params = []; // Mảng cho prepared statement
$types = ""; // Chuỗi kiểu dữ liệu cho prepared statement

// Thêm điều kiện tìm kiếm (nếu có)
if ($search) {
    $where[] = "product_name LIKE ?";
    $types .= "s"; // 's' for string
    $searchTerm = "%" . $search . "%"; // Thêm % cho LIKE
    $params[] = &$searchTerm; // Thêm vào mảng params (tham chiếu)
}

// Thêm điều kiện category (nếu có)
if ($category) {
    $where[] = "category_id = ?"; // Giả sử cột khóa ngoại là category_id
    $types .= "i"; // 'i' for integer
    $params[] = &$category; // Thêm vào mảng params (tham chiếu)
}

// Ghép các điều kiện WHERE nếu có
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Sử dụng Prepared Statements để bảo mật hơn
$stmt = $conn->prepare($sql);

if ($stmt === false) {
     echo json_encode(["error" => "Lỗi chuẩn bị câu lệnh SQL: " . $conn->error]);
     $conn->close();
     exit();
}

// Gắn tham số nếu có
if (!empty($params)) {
    // Cần gọi bind_param với danh sách tham số trực tiếp
    // Ví dụ: $stmt->bind_param("si", $searchTerm, $category); nếu có cả hai
     call_user_func_array([$stmt, 'bind_param'], array_merge([$types], $params));
}

// Thực thi câu lệnh
$stmt->execute();
$result = $stmt->get_result();
$products = [];

if ($result) { // Kiểm tra xem có kết quả không
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = [
                "product_id" => $row["product_id"],
                "product_name" => $row["product_name"],
                "product_price" => $row["product_price"],
                // Đảm bảo đường dẫn ảnh đúng từ gốc web hoặc là URL tuyệt đối
                "product_image" => "images/" . $row["product_img"]
            ];
        }
    }
} else {
     echo json_encode(["error" => "Lỗi thực thi câu lệnh SQL: " . $stmt->error]);
     $stmt->close();
     $conn->close();
     exit();
}


// Trả về dữ liệu JSON với Unicode chuẩn
echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); // Thêm PRETTY_PRINT để dễ đọc khi debug

// Đóng statement và kết nối
$stmt->close();
$conn->close();
?>