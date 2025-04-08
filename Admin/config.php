<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "se07102_sdlc";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

?>
