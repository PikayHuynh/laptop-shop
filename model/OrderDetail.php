<?php

require_once __DIR__ . '/Model.php';

class OrderDetail extends Model {
    protected $table = 'order_detail';
    protected $primaryKey = 'order_detail_id';
    
    /**
     * Get order details by order ID
     * @param int $orderId
     * @return array
     */
    public function getByOrderId($orderId) {
        return $this->findBy('order_id', $orderId);
    }
    
    /**
     * Get order details with product information
     * @param int $orderId
     * @return array
     */
    public function getByOrderIdWithProducts($orderId) {
        $sql = "SELECT od.*, p.name as product_name, p.price as current_price
                FROM {$this->table} od
                LEFT JOIN products p ON od.product_id = p.product_id
                WHERE od.order_id = :order_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Calculate total for an order
     * @param int $orderId
     * @return float
     */
    public function getOrderTotal($orderId) {
        $sql = "SELECT SUM(quantity * price) as total FROM {$this->table} WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    /**
     * Create multiple order details at once
     * @param array $items Array of items with order_id, product_id, quantity, price
     * @return bool
     */
    public function createMultiple($items) {
        $this->conn->beginTransaction();
        try {
            foreach ($items as $item) {
                $this->create($item);
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
