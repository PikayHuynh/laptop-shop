<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/cart.php';
require_once __DIR__ . '/../model/cart_detail.php';

class CartController {
    private $cartModel;
    private $cartDetailModel;

    public function __construct($db) {
        $this->cartModel = new cart($db);
        $this->cartDetailModel = new cart_detail($db);
    }

    public function index() {
        $perPage = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = $this->cartModel->getPaginated($page, $perPage);
        
        return [
            'view' => 'admin/cart/list',
            'data' => [
                'carts' => $data['data'],
                'totalPages' => $data['totalPages'],
                'page' => $data['page']
            ]
        ];
    }

    public function view() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=cart&action=index');
            exit;
        }

        $cart = $this->cartModel->getById($id);
        if (!$cart) {
            header('Location: /laptop_shop/index.php?controller=cart&action=index');
            exit;
        }

        $items = $this->cartDetailModel->getByCartId($id);

        return [
            'view' => 'admin/cart/view',
            'data' => [
                'cart' => $cart,
                'items' => $items
            ]
        ];
    }

    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=cart&action=index');
            exit;
        }

        $this->cartDetailModel->deleteByCartId($id);
        $this->cartModel->delete($id);
        header('Location: /laptop_shop/index.php?controller=cart&action=index');
        exit;
    }
}
