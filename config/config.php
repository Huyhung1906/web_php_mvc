<?php
$host = 'localhost';      // XAMPP chạy MySQL trên localhost
$dbname = 'shoes_web';  // Tên database bạn vừa tạo
$username = 'root';       // Tên tài khoản mặc định
$password = 'johndoe@123';           // Mật khẩu trống trong XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}
?>
