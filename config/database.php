<?php
$GLOBALS['host'] = 'localhost';
$GLOBALS['dbname'] = 'laptop_shop';
$GLOBALS['username'] = 'root';
$GLOBALS['password'] = '';
$GLOBALS['charset'] = 'utf8mb4';

try {
    $dsn = "mysql:host={$GLOBALS['host']};dbname={$GLOBALS['dbname']};charset={$GLOBALS['charset']}";
    $GLOBALS['conn'] = new PDO($dsn, $GLOBALS['username'], $GLOBALS['password']);
    $GLOBALS['conn']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Make connection available globally
$conn = $GLOBALS['conn'];