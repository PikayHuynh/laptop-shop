<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../model/factory.php';

    $factoryModel = new factory($conn);
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$id) {
        header("Location: list.php");
        exit;
    }

    $factoryModel->delete($id);
    header("Location: list.php");
    exit;
?>
