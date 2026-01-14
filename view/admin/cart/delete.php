<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../model/cart.php';
    require_once __DIR__ . '/../../../model/cart_detail.php';

    $cartModel = new cart($conn);
    $cartDetailModel = new cart_detail($conn);
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$id) {
        header("Location: list.php");
        exit;
    }

    // Delete cart items first
    $cartDetailModel->deleteByCartId($id);
    
    // Then delete the cart
    $cartModel->delete($id);
    
    header("Location: list.php");
    exit;
?>
