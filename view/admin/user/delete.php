<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../model/user.php';

    $userModel = new user($conn);
    
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if (!$id) {
        header("Location: list.php");
        exit;
    }

    $userModel->delete($id);
    header("Location: list.php");
    exit;
?>
