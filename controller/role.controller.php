<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/role.php';

class RoleController {
    private $roleModel;

    public function __construct($db) {
        $this->roleModel = new role($db);
    }

    public function index() {
        $perPage = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = $this->roleModel->getPaginated($page, $perPage);
        
        return [
            'view' => 'admin/role/list',
            'data' => [
                'roles' => $data['data'],
                'totalPages' => $data['totalPages'],
                'page' => $data['page']
            ]
        ];
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? ''
            ];

            if (empty($data['name'])) {
                return [
                    'view' => 'admin/role/create',
                    'data' => ['error' => 'Role name is required.']
                ];
            }

            if ($this->roleModel->create($data)) {
                header('Location: /laptop_shop/index.php?controller=role&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/role/create',
                    'data' => ['error' => 'Failed to create role.']
                ];
            }
        }

        return [
            'view' => 'admin/role/create',
            'data' => []
        ];
    }

    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=role&action=index');
            exit;
        }

        $role = $this->roleModel->getById($id);
        if (!$role) {
            header('Location: /laptop_shop/index.php?controller=role&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? ''
            ];

            if (empty($data['name'])) {
                return [
                    'view' => 'admin/role/edit',
                    'data' => [
                        'error' => 'Role name is required.',
                        'role' => $role
                    ]
                ];
            }

            if ($this->roleModel->update($id, $data)) {
                header('Location: /laptop_shop/index.php?controller=role&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/role/edit',
                    'data' => [
                        'error' => 'Failed to update role.',
                        'role' => $role
                    ]
                ];
            }
        }

        return [
            'view' => 'admin/role/edit',
            'data' => ['role' => $role]
        ];
    }

    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=role&action=index');
            exit;
        }

        $this->roleModel->delete($id);
        header('Location: /laptop_shop/index.php?controller=role&action=index');
        exit;
    }
}
