<?php

require_once __DIR__ . '/Model.php';

class CartDetail extends Model {
    protected $table = 'cart_detail';
    protected $primaryKey = 'cart_detail_id';
    
    /**
     * Get cart details by cart ID
     * @param int $cartId
     * @return array
     */
    public function getByCartId($cartId) {
        return $this->findBy('cart_id', $cartId);
    }
    
    /**
     * Get cart details with product information
     * @param int $cartId
     * @return array
     */
    public function getByCartIdWithProducts($cartId) {
        $sql = "SELECT cd.*, p.name as product_name, p.price, p.quantity as product_quantity
                FROM {$this->table} cd
                LEFT JOIN products p ON cd.product_id = p.product_id
                WHERE cd.cart_id = :cart_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get cart detail by cart ID and product ID
     * @param int $cartId
     * @param int $productId
     * @return array|false
     */
    public function getByCartAndProduct($cartId, $productId) {
        $sql = "SELECT * FROM {$this->table} WHERE cart_id = :cart_id AND product_id = :product_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Add or update product in cart
     * @param int $cartId
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function addOrUpdate($cartId, $productId, $quantity) {
        $existing = $this->getByCartAndProduct($cartId, $productId);
        
        if ($existing) {
            // Update quantity
            $newQuantity = $existing['quantity'] + $quantity;
            return $this->update($existing['cart_detail_id'], ['quantity' => $newQuantity]);
        } else {
            // Create new
            return $this->create([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }
    
    /**
     * Delete all items from cart
     * @param int $cartId
     * @return bool
     */
    public function deleteByCartId($cartId) {
        $sql = "DELETE FROM {$this->table} WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cart_id', $cartId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
