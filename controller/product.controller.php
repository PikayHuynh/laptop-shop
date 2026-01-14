<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/product.php';
require_once __DIR__ . '/../model/factory.php';
require_once __DIR__ . '/../model/category.php';

class ProductController {
    private $productModel;
    private $factoryModel;
    private $categoryModel;

    public function __construct($db) {
        $this->productModel = new product($db);
        $this->factoryModel = new factory($db);
        $this->categoryModel = new category($db);
    }

    public function index() {
        $perPage = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = $this->productModel->getPaginated($page, $perPage);
        
        return [
            'view' => 'admin/product/list',
            'data' => [
                'products' => $data['data'],
                'totalPages' => $data['totalPages'],
                'page' => $data['page']
            ]
        ];
    }

    public function create() {
        $factories = $this->factoryModel->getAll();
        $categories = $this->categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'price' => $_POST['price'] ?? '',
                'quantity' => $_POST['quantity'] ?? 0,
                'factory_id' => $_POST['factory_id'] ?? 0,
                'category_id' => $_POST['category_id'] ?? 0
            ];

            if (empty($data['name'])) {
                return [
                    'view' => 'admin/product/create',
                    'data' => [
                        'error' => 'Product name is required.',
                        'factories' => $factories,
                        'categories' => $categories
                    ]
                ];
            }
            if (empty($data['price'])) {
                return [
                    'view' => 'admin/product/create',
                    'data' => [
                        'error' => 'Price is required.',
                        'factories' => $factories,
                        'categories' => $categories
                    ]
                ];
            }

            if ($this->productModel->create($data)) {
                header('Location: /laptop_shop/index.php?controller=product&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/product/create',
                    'data' => [
                        'error' => 'Failed to create product.',
                        'factories' => $factories,
                        'categories' => $categories
                    ]
                ];
            }
        }

        return [
            'view' => 'admin/product/create',
            'data' => [
                'factories' => $factories,
                'categories' => $categories
            ]
        ];
    }

    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=product&action=index');
            exit;
        }

        $product = $this->productModel->getById($id);
        if (!$product) {
            header('Location: /laptop_shop/index.php?controller=product&action=index');
            exit;
        }

        $factories = $this->factoryModel->getAll();
        $categories = $this->categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'price' => $_POST['price'] ?? '',
                'quantity' => $_POST['quantity'] ?? 0,
                'factory_id' => $_POST['factory_id'] ?? 0,
                'category_id' => $_POST['category_id'] ?? 0
            ];

            if (empty($data['name'])) {
                return [
                    'view' => 'admin/product/edit',
                    'data' => [
                        'error' => 'Product name is required.',
                        'product' => $product,
                        'factories' => $factories,
                        'categories' => $categories
                    ]
                ];
            }
            if (empty($data['price'])) {
                return [
                    'view' => 'admin/product/edit',
                    'data' => [
                        'error' => 'Price is required.',
                        'product' => $product,
                        'factories' => $factories,
                        'categories' => $categories
                    ]
                ];
            }

            if ($this->productModel->update($id, $data)) {
                header('Location: /laptop_shop/index.php?controller=product&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/product/edit',
                    'data' => [
                        'error' => 'Failed to update product.',
                        'product' => $product,
                        'factories' => $factories,
                        'categories' => $categories
                    ]
                ];
            }
        }

        return [
            'view' => 'admin/product/edit',
            'data' => [
                'product' => $product,
                'factories' => $factories,
                'categories' => $categories
            ]
        ];
    }

    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=product&action=index');
            exit;
        }

        $this->productModel->delete($id);
        header('Location: /laptop_shop/index.php?controller=product&action=index');
        exit;
    }
}
