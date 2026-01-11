<?php
$host = 'localhost';
$dbname = 'laptop_shop';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    ?>
        <div>Kết nối thành công<div/>
    <?php
} catch (PDOException $e) {
    ?>
        <div>Kết nối thất bại<div/>
    <?php
}