<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/role.php';

class UserController {
    private $userModel;
    private $roleModel;

    public function __construct($db) {
        $this->userModel = new user($db);
        $this->roleModel = new role($db);
    }

    public function index() {
        $perPage = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = $this->userModel->getPaginated($page, $perPage);
        
        return [
            'view' => 'admin/user/list',
            'data' => [
                'users' => $data['data'],
                'totalPages' => $data['totalPages'],
                'page' => $data['page']
            ]
        ];
    }

    public function create() {
        $roles = $this->roleModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'full_name' => $_POST['full_name'] ?? '',
                'role_id' => $_POST['role_id'] ?? 0
            ];

            if (empty($data['email'])) {
                return [
                    'view' => 'admin/user/create',
                    'data' => [
                        'error' => 'Email is required.',
                        'roles' => $roles
                    ]
                ];
            }
            if (empty($data['password'])) {
                return [
                    'view' => 'admin/user/create',
                    'data' => [
                        'error' => 'Password is required.',
                        'roles' => $roles
                    ]
                ];
            }
            if (empty($data['full_name'])) {
                return [
                    'view' => 'admin/user/create',
                    'data' => [
                        'error' => 'Full name is required.',
                        'roles' => $roles
                    ]
                ];
            }

            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            if ($this->userModel->create($data)) {
                header('Location: /laptop_shop/index.php?controller=user&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/user/create',
                    'data' => [
                        'error' => 'Failed to create user.',
                        'roles' => $roles
                    ]
                ];
            }
        }

        return [
            'view' => 'admin/user/create',
            'data' => ['roles' => $roles]
        ];
    }

    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=user&action=index');
            exit;
        }

        $user = $this->userModel->getById($id);
        if (!$user) {
            header('Location: /laptop_shop/index.php?controller=user&action=index');
            exit;
        }

        $roles = $this->roleModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [];
            
            if (!empty($_POST['email'])) {
                $data['email'] = $_POST['email'];
            }
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }
            if (!empty($_POST['full_name'])) {
                $data['full_name'] = $_POST['full_name'];
            }
            if (!empty($_POST['role_id'])) {
                $data['role_id'] = $_POST['role_id'];
            }

            if (empty($data)) {
                return [
                    'view' => 'admin/user/edit',
                    'data' => [
                        'error' => 'Please fill at least one field.',
                        'user' => $user,
                        'roles' => $roles
                    ]
                ];
            }

            if ($this->userModel->update($id, $data)) {
                header('Location: /laptop_shop/index.php?controller=user&action=index');
                exit;
            } else {
                return [
                    'view' => 'admin/user/edit',
                    'data' => [
                        'error' => 'Failed to update user.',
                        'user' => $user,
                        'roles' => $roles
                    ]
                ];
            }
        }

        return [
            'view' => 'admin/user/edit',
            'data' => [
                'user' => $user,
                'roles' => $roles
            ]
        ];
    }

    public function delete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header('Location: /laptop_shop/index.php?controller=user&action=index');
            exit;
        }

        $this->userModel->delete($id);
        header('Location: /laptop_shop/index.php?controller=user&action=index');
        exit;
    }
}
