<?php

require_once __DIR__ . "/../config/database.php";

class product {
    private $conn;
    private $table = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT p.product_id, p.name, p.price, p.quantity, p.factory_id, f.name as factory_name, p.category_id, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN factories f ON p.factory_id = f.factory_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            ORDER BY p.product_id DESC");
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

        $stmt = $this->conn->prepare("SELECT p.product_id, p.name, p.price, p.quantity, p.factory_id, f.name as factory_name, p.category_id, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN factories f ON p.factory_id = f.factory_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            ORDER BY p.product_id DESC
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
        $stmt = $this->conn->prepare("SELECT p.product_id, p.name, p.price, p.quantity, p.factory_id, f.name as factory_name, p.category_id, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN factories f ON p.factory_id = f.factory_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.product_id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByFactoryId($factory_id) {
        $stmt = $this->conn->prepare("SELECT p.product_id, p.name, p.price, p.quantity, p.factory_id, f.name as factory_name, p.category_id, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN factories f ON p.factory_id = f.factory_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.factory_id = :factory_id ORDER BY p.product_id DESC");
        $stmt->bindParam(':factory_id', $factory_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategoryId($category_id) {
        $stmt = $this->conn->prepare("SELECT p.product_id, p.name, p.price, p.quantity, p.factory_id, f.name as factory_name, p.category_id, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN factories f ON p.factory_id = f.factory_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.category_id = :category_id ORDER BY p.product_id DESC");
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, price, quantity, factory_id, category_id) VALUES (:name, :price, :quantity, :factory_id, :category_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
        $stmt->bindParam(':factory_id', $data['factory_id'], PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $data['category_id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params[':name'] = $data['name'];
        }
        if (isset($data['price'])) {
            $fields[] = 'price = :price';
            $params[':price'] = $data['price'];
        }
        if (isset($data['quantity'])) {
            $fields[] = 'quantity = :quantity';
            $params[':quantity'] = $data['quantity'];
        }
        if (isset($data['factory_id'])) {
            $fields[] = 'factory_id = :factory_id';
            $params[':factory_id'] = $data['factory_id'];
        }
        if (isset($data['category_id'])) {
            $fields[] = 'category_id = :category_id';
            $params[':category_id'] = $data['category_id'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE product_id = :id";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $pkey => $pval) {
            if (in_array($pkey, [':quantity', ':factory_id', ':category_id', ':id'])) {
                $stmt->bindValue($pkey, $pval, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($pkey, $pval);
            }
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE product_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
