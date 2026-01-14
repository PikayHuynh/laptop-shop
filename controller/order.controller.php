<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/order.php';
require_once __DIR__ . '/../model/order_detail.php';

class OrderController {
    private $orderModel;
    private $orderDetailModel;

    public function __construct($db) {
        $this->orderModel = new order($db);
        $this->orderDetailModel = new order_detail($db);
    }

    public function index() {
        $perPage = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = $this->orderModel->getPaginated($page, $perPage);
        
        return [
            'view' => 'admin/order/list',
            'data' => [
                'orders' => $data['data'],
                'totalPages' => $data['totalPages'],
                'page' => $data['page']
            ]
        ];
    }

    public function view() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=order&action=index');
            exit;
        }

        $order = $this->orderModel->getById($id);
        if (!$order) {
            header('Location: /laptop_shop/index.php?controller=order&action=index');
            exit;
        }

        $items = $this->orderDetailModel->getByOrderId($id);

        return [
            'view' => 'admin/order/view',
            'data' => [
                'order' => $order,
                'items' => $items
            ]
        ];
    }

    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=order&action=index');
            exit;
        }

        $order = $this->orderModel->getById($id);
        if (!$order) {
            header('Location: /laptop_shop/index.php?controller=order&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'status' => $_POST['status'] ?? ''
            ];

            if (empty($data['status'])) {
                return [
                    'view' => 'admin/order/edit',
                    'data' => [
                        'error' => 'Status is required.',
                        'order' => $order
                    ]
                ];
            }

            if ($this->orderModel->update($id, $data)) {
                header('Location: /laptop_shop/index.php?controller=order&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/order/edit',
                    'data' => [
                        'error' => 'Failed to update order.',
                        'order' => $order
                    ]
                ];
            }
        }

        return [
            'view' => 'admin/order/edit',
            'data' => ['order' => $order]
        ];
    }

    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=order&action=index');
            exit;
        }

        $this->orderDetailModel->deleteByOrderId($id);
        $this->orderModel->delete($id);
        header('Location: /laptop_shop/index.php?controller=order&action=index');
        exit;
    }
}
