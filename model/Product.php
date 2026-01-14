<?php

require_once __DIR__ . '/Model.php';

class Product extends Model {
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    
    /**
     * Get products by category
     * @param int $categoryId
     * @return array
     */
    public function getByCategory($categoryId) {
        return $this->findBy('category_id', $categoryId);
    }
    
    /**
     * Get products by factory
     * @param int $factoryId
     * @return array
     */
    public function getByFactory($factoryId) {
        return $this->findBy('factory_id', $factoryId);
    }
    
    /**
     * Get products with category and factory information
     * @return array
     */
    public function getAllWithDetails() {
        $sql = "SELECT p.*, c.name as category_name, f.name as factory_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN factories f ON p.factory_id = f.factory_id
                ORDER BY p.product_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get product with category and factory information
     * @param int $id
     * @return array|false
     */
    public function getByIdWithDetails($id) {
        $sql = "SELECT p.*, c.name as category_name, f.name as factory_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN factories f ON p.factory_id = f.factory_id
                WHERE p.{$this->primaryKey} = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Search products by name
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm) {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE :search";
        $stmt = $this->conn->prepare($sql);
        $search = '%' . $searchTerm . '%';
        $stmt->bindValue(':search', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update product quantity
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity($id, $quantity) {
        return $this->update($id, ['quantity' => $quantity]);
    }
}
