<?php
session_start();
session_destroy(); // Hủy toàn bộ session
header("Location: login.html"); // Chuyển hướng về trang đăng nhập
exit();
?>
