<?php

require_once __DIR__ . "/../config/database.php";

class order {
    private $conn;
    private $table = "orders";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT o.order_id, o.user_id, u.full_name, o.order_date, o.status
            FROM {$this->table} o
            LEFT JOIN users u ON o.user_id = u.user_id
            ORDER BY o.order_id DESC");
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

        $stmt = $this->conn->prepare("SELECT o.order_id, o.user_id, u.full_name, o.order_date, o.status
            FROM {$this->table} o
            LEFT JOIN users u ON o.user_id = u.user_id
            ORDER BY o.order_id DESC
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
        $stmt = $this->conn->prepare("SELECT o.order_id, o.user_id, u.full_name, o.order_date, o.status
            FROM {$this->table} o
            LEFT JOIN users u ON o.user_id = u.user_id
            WHERE o.order_id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT o.order_id, o.user_id, o.order_date, o.status
            FROM {$this->table} o
            WHERE o.user_id = :user_id ORDER BY o.order_date DESC");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, status) VALUES (:user_id, :status)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'] ?? 'pending');
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['user_id'])) {
            $fields[] = 'user_id = :user_id';
            $params[':user_id'] = $data['user_id'];
        }
        if (isset($data['status'])) {
            $fields[] = 'status = :status';
            $params[':status'] = $data['status'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE order_id = :id";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $pkey => $pval) {
            if (in_array($pkey, [':user_id', ':id'])) {
                $stmt->bindValue($pkey, $pval, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($pkey, $pval);
            }
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE order_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
