<?php

require_once __DIR__ . '/Model.php';

class Cart extends Model {
    protected $table = 'carts';
    protected $primaryKey = 'cart_id';
    
    /**
     * Get cart by user ID
     * @param int $userId
     * @return array|false
     */
    public function getByUserId($userId) {
        return $this->findOneBy('user_id', $userId);
    }
    
    /**
     * Get or create cart for user
     * @param int $userId
     * @return array
     */
    public function getOrCreate($userId) {
        $cart = $this->getByUserId($userId);
        if (!$cart) {
            $cartId = $this->create(['user_id' => $userId]);
            $cart = $this->getById($cartId);
        }
        return $cart;
    }
}
