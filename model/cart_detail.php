<?php

require_once __DIR__ . "/../config/database.php";

class cart_detail {
    private $conn;
    private $table = "cart_detail";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT cd.cart_detail_id, cd.cart_id, cd.product_id, cd.quantity, p.name as product_name, p.price
            FROM {$this->table} cd
            LEFT JOIN products p ON cd.product_id = p.product_id
            ORDER BY cd.cart_detail_id DESC");
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

        $stmt = $this->conn->prepare("SELECT cd.cart_detail_id, cd.cart_id, cd.product_id, cd.quantity, p.name as product_name, p.price
            FROM {$this->table} cd
            LEFT JOIN products p ON cd.product_id = p.product_id
            ORDER BY cd.cart_detail_id DESC
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
        $stmt = $this->conn->prepare("SELECT cd.cart_detail_id, cd.cart_id, cd.product_id, cd.quantity, p.name as product_name, p.price
            FROM {$this->table} cd
            LEFT JOIN products p ON cd.product_id = p.product_id
            WHERE cd.cart_detail_id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCartId($cart_id) {
        $stmt = $this->conn->prepare("SELECT cd.cart_detail_id, cd.cart_id, cd.product_id, cd.quantity, p.name as product_name, p.price
            FROM {$this->table} cd
            LEFT JOIN products p ON cd.product_id = p.product_id
            WHERE cd.cart_id = :cart_id ORDER BY cd.cart_detail_id DESC");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cart_id', $data['cart_id'], PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $data['product_id'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['cart_id'])) {
            $fields[] = 'cart_id = :cart_id';
            $params[':cart_id'] = $data['cart_id'];
        }
        if (isset($data['product_id'])) {
            $fields[] = 'product_id = :product_id';
            $params[':product_id'] = $data['product_id'];
        }
        if (isset($data['quantity'])) {
            $fields[] = 'quantity = :quantity';
            $params[':quantity'] = $data['quantity'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE cart_detail_id = :id";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $pkey => $pval) {
            if (in_array($pkey, [':cart_id', ':product_id', ':quantity', ':id'])) {
                $stmt->bindValue($pkey, $pval, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($pkey, $pval);
            }
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE cart_detail_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteByCartId($cart_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE cart_id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
