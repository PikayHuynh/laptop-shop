<?php

require_once __DIR__ . "/../config/database.php";

class cart {
    private $conn;
    private $table = "carts";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT c.cart_id, c.user_id, u.full_name, c.created_at
            FROM {$this->table} c
            LEFT JOIN users u ON c.user_id = u.user_id
            ORDER BY c.cart_id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated($page = 1, $perPage = 10) {
        $page = (int)$page;
        if ($page < 1) $page = 1;

        $countStmt = $this->conn->prepare("SELECT COUNT(*) as cnt FROM {$this->table}");
        $countStmt->execute();
        $total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['cnt'];

        $totalPages = (int)ceil($total / $perPage);
        if ($totalPages < 1) $totalPages = 1;
        if ($page > $totalPages) $page = $totalPages;

        $offset = ($page - 1) * $perPage;

        $stmt = $this->conn->prepare("SELECT c.cart_id, c.user_id, u.full_name, c.created_at
            FROM {$this->table} c
            LEFT JOIN users u ON c.user_id = u.user_id
            ORDER BY c.cart_id DESC
            LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $rows,
            'total' => $total,
            'totalPages' => $totalPages,
            'page' => $page,
        ];
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT c.cart_id, c.user_id, u.full_name, c.created_at
            FROM {$this->table} c
            LEFT JOIN users u ON c.user_id = u.user_id
            WHERE c.cart_id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT c.cart_id, c.user_id, c.created_at
            FROM {$this->table} c
            WHERE c.user_id = :user_id ORDER BY c.created_at DESC LIMIT 1");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id) VALUES (:user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        if (!isset($data['user_id'])) {
            return false;
        }
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET user_id = :user_id WHERE cart_id = :id");
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE cart_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
