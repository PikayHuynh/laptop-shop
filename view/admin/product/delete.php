<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../model/product.php';

    $productModel = new product($conn);
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$id) {
        header("Location: list.php");
        exit;
    }

    $productModel->delete($id);
    header("Location: list.php");
    exit;
?>
