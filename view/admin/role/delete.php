<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../model/role.php';

    $roleModel = new role($conn);
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$id) {
        header("Location: list.php");
        exit;
    }

    $roleModel->delete($id);
    header("Location: list.php");
    exit;
?>
