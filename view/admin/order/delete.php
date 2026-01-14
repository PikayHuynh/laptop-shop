<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../model/order.php';
    require_once __DIR__ . '/../../../model/order_detail.php';

    $orderModel = new order($conn);
    $orderDetailModel = new order_detail($conn);
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$id) {
        header("Location: list.php");
        exit;
    }

    // Delete order items first
    $orderDetailModel->deleteByOrderId($id);
    
    // Then delete the order
    $orderModel->delete($id);
    
    header("Location: list.php");
    exit;
?>
