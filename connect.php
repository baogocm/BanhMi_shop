<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=banhmi_shop','root','');
} catch (PDOException $e) {
    echo 'Kết nối thất bại: ' . $e->getMessage() . '. Vui lòng kiểm tra lại thông tin kết nối và thử lại.';
    exit;
}
?>
