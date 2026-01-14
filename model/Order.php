<?php

require_once __DIR__ . '/Model.php';

class Order extends Model {
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    
    /**
     * Get orders by user ID
     * @param int $userId
     * @return array
     */
    public function getByUserId($userId) {
        return $this->findBy('user_id', $userId);
    }
    
    /**
     * Get orders with user information
     * @return array
     */
    public function getAllWithUsers() {
        $sql = "SELECT o.*, u.email, u.full_name 
                FROM {$this->table} o
                LEFT JOIN users u ON o.user_id = u.user_id
                ORDER BY o.order_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get order with user information
     * @param int $id
     * @return array|false
     */
    public function getByIdWithUser($id) {
        $sql = "SELECT o.*, u.email, u.full_name 
                FROM {$this->table} o
                LEFT JOIN users u ON o.user_id = u.user_id
                WHERE o.{$this->primaryKey} = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get orders by status
     * @param string $status
     * @return array
     */
    public function getByStatus($status) {
        return $this->findBy('status', $status);
    }
    
    /**
     * Update order status
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status) {
        return $this->update($id, ['status' => $status]);
    }
}
