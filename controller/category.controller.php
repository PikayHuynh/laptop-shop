<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/category.php';

class CategoryController {
    private $categoryModel;

    public function __construct($db) {
        $this->categoryModel = new category($db);
    }

    public function index() {
        $perPage = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = $this->categoryModel->getPaginated($page, $perPage);
        
        return [
            'view' => 'admin/category/list',
            'data' => [
                'categories' => $data['data'],
                'totalPages' => $data['totalPages'],
                'page' => $data['page']
            ]
        ];
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];

            if (empty($data['name'])) {
                return [
                    'view' => 'admin/category/create',
                    'data' => ['error' => 'Category name is required.']
                ];
            }

            if ($this->categoryModel->create($data)) {
                header('Location: /laptop_shop/index.php?controller=category&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/category/create',
                    'data' => ['error' => 'Failed to create category.']
                ];
            }
        }

        return [
            'view' => 'admin/category/create',
            'data' => []
        ];
    }

    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=category&action=index');
            exit;
        }

        $category = $this->categoryModel->getById($id);
        if (!$category) {
            header('Location: /laptop_shop/index.php?controller=category&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];

            if (empty($data['name'])) {
                return [
                    'view' => 'admin/category/edit',
                    'data' => [
                        'error' => 'Category name is required.',
                        'category' => $category
                    ]
                ];
            }

            if ($this->categoryModel->update($id, $data)) {
                header('Location: /laptop_shop/index.php?controller=category&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/category/edit',
                    'data' => [
                        'error' => 'Failed to update category.',
                        'category' => $category
                    ]
                ];
            }
        }

        return [
            'view' => 'admin/category/edit',
            'data' => ['category' => $category]
        ];
    }

    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=category&action=index');
            exit;
        }

        $this->categoryModel->delete($id);
        header('Location: /laptop_shop/index.php?controller=category&action=index');
        exit;
    }
}
