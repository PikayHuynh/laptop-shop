<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/factory.php';

class FactoryController {
    private $factoryModel;

    public function __construct($db) {
        $this->factoryModel = new factory($db);
    }

    public function index() {
        $perPage = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = $this->factoryModel->getPaginated($page, $perPage);
        
        return [
            'view' => 'admin/factory/list',
            'data' => [
                'factories' => $data['data'],
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
                    'view' => 'admin/factory/create',
                    'data' => ['error' => 'Factory name is required.']
                ];
            }

            if ($this->factoryModel->create($data)) {
                header('Location: /laptop_shop/index.php?controller=factory&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/factory/create',
                    'data' => ['error' => 'Failed to create factory.']
                ];
            }
        }

        return [
            'view' => 'admin/factory/create',
            'data' => []
        ];
    }

    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=factory&action=index');
            exit;
        }

        $factory = $this->factoryModel->getById($id);
        if (!$factory) {
            header('Location: /laptop_shop/index.php?controller=factory&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];

            if (empty($data['name'])) {
                return [
                    'view' => 'admin/factory/edit',
                    'data' => [
                        'error' => 'Factory name is required.',
                        'factory' => $factory
                    ]
                ];
            }

            if ($this->factoryModel->update($id, $data)) {
                header('Location: /laptop_shop/index.php?controller=factory&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/factory/edit',
                    'data' => [
                        'error' => 'Failed to update factory.',
                        'factory' => $factory
                    ]
                ];
            }
        }

        return [
            'view' => 'admin/factory/edit',
            'data' => ['factory' => $factory]
        ];
    }

    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=factory&action=index');
            exit;
        }

        $this->factoryModel->delete($id);
        header('Location: /laptop_shop/index.php?controller=factory&action=index');
        exit;
    }
}
