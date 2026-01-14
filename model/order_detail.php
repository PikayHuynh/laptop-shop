<?php

require_once __DIR__ . "/../config/database.php";

class order_detail {
    private $conn;
    private $table = "order_detail";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT od.order_detail_id, od.order_id, od.product_id, od.quantity, od.price, p.name as product_name
            FROM {$this->table} od
            LEFT JOIN products p ON od.product_id = p.product_id
            ORDER BY od.order_detail_id DESC");
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

        $stmt = $this->conn->prepare("SELECT od.order_detail_id, od.order_id, od.product_id, od.quantity, od.price, p.name as product_name
            FROM {$this->table} od
            LEFT JOIN products p ON od.product_id = p.product_id
            ORDER BY od.order_detail_id DESC
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
        $stmt = $this->conn->prepare("SELECT od.order_detail_id, od.order_id, od.product_id, od.quantity, od.price, p.name as product_name
            FROM {$this->table} od
            LEFT JOIN products p ON od.product_id = p.product_id
            WHERE od.order_detail_id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByOrderId($order_id) {
        $stmt = $this->conn->prepare("SELECT od.order_detail_id, od.order_id, od.product_id, od.quantity, od.price, p.name as product_name
            FROM {$this->table} od
            LEFT JOIN products p ON od.product_id = p.product_id
            WHERE od.order_id = :order_id ORDER BY od.order_detail_id DESC");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':order_id', $data['order_id'], PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $data['product_id'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
        $stmt->bindParam(':price', $data['price']);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['order_id'])) {
            $fields[] = 'order_id = :order_id';
            $params[':order_id'] = $data['order_id'];
        }
        if (isset($data['product_id'])) {
            $fields[] = 'product_id = :product_id';
            $params[':product_id'] = $data['product_id'];
        }
        if (isset($data['quantity'])) {
            $fields[] = 'quantity = :quantity';
            $params[':quantity'] = $data['quantity'];
        }
        if (isset($data['price'])) {
            $fields[] = 'price = :price';
            $params[':price'] = $data['price'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE order_detail_id = :id";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $pkey => $pval) {
            if (in_array($pkey, [':order_id', ':product_id', ':quantity', ':id'])) {
                $stmt->bindValue($pkey, $pval, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($pkey, $pval);
            }
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE order_detail_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteByOrderId($order_id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
