<?php

namespace admin;

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../model/User.php';
require_once __DIR__ . '/../../model/Role.php';

class UserController extends \Controller {
    private $userModel;
    private $roleModel;

    public function __construct() {
        $this->userModel = new \User();
        $this->roleModel = new \Role();
    }

    /**
     * List all users
     */
    public function listUsers() {
        $users = $this->userModel->getAll();
        
        // Get role names for each user
        foreach ($users as &$user) {
            $role = $this->roleModel->getById($user['role_id']);
            $user['role_name'] = $role ? $role['name'] : 'Unknown';
        }
        
        $this->loadView('admin/user/list.php', ['users' => $users]);
    }

    /**
     * Show add user form
     */
    public function addUser() {
        $roles = $this->roleModel->getAll();
        $this->loadView('admin/user/add.php', ['roles' => $roles]);
    }

    /**
     * Create a new user
     */
    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->getBaseUrl() . '/admin/add-user');
            return;
        }

        $data = [
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'role_id' => $_POST['role_id'] ?? 0
        ];

        // Validate input
        if (empty($data['email']) || empty($data['password']) || empty($data['full_name']) || empty($data['role_id'])) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect($this->getBaseUrl() . '/admin/add-user');
            return;
        }

        // Check if email already exists
        if ($this->userModel->getByEmail($data['email'])) {
            $_SESSION['error'] = 'Email already exists';
            $this->redirect($this->getBaseUrl() . '/admin/add-user');
            return;
        }

        // Create user
        if ($this->userModel->create($data)) {
            $_SESSION['success'] = 'User created successfully';
            $this->redirect($this->getBaseUrl() . '/admin/users');
        } else {
            $_SESSION['error'] = 'Failed to create user';
            $this->redirect($this->getBaseUrl() . '/admin/add-user');
        }
    }

    /**
     * Show edit user form
     */
    public function editUser() {
        $userId = $_GET['id'] ?? null;
        
        if (!$userId) {
            $_SESSION['error'] = 'User not found';
            $this->redirect($this->getBaseUrl() . '/admin/users');
            return;
        }

        $user = $this->userModel->getById($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'User not found';
            $this->redirect($this->getBaseUrl() . '/admin/users');
            return;
        }

        $roles = $this->roleModel->getAll();
        $this->loadView('admin/user/edit.php', ['user' => $user, 'roles' => $roles]);
    }

    /**
     * Update user
     */
    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->getBaseUrl() . '/admin/users');
            return;
        }

        $userId = $_POST['user_id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = 'User not found';
            $this->redirect($this->getBaseUrl() . '/admin/users');
            return;
        }

        $user = $this->userModel->getById($userId);

        if (!$user) {
            $_SESSION['error'] = 'User not found';
            $this->redirect($this->getBaseUrl() . '/admin/users');
            return;
        }

        $data = [
            'email' => $_POST['email'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'role_id' => $_POST['role_id'] ?? 0
        ];

        // Validate input
        if (empty($data['email']) || empty($data['full_name']) || empty($data['role_id'])) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect($this->getBaseUrl() . '/admin/edit-user?id=' . $userId);
            return;
        }

        // Check if new email already exists (different from current email)
        if ($data['email'] !== $user['email'] && $this->userModel->getByEmail($data['email'])) {
            $_SESSION['error'] = 'Email already exists';
            $this->redirect($this->getBaseUrl() . '/admin/edit-user?id=' . $userId);
            return;
        }

        // Handle password update
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        // Update user
        if ($this->userModel->update($userId, $data)) {
            $_SESSION['success'] = 'User updated successfully';
            $this->redirect($this->getBaseUrl() . '/admin/users');
        } else {
            $_SESSION['error'] = 'Failed to update user';
            $this->redirect($this->getBaseUrl() . '/admin/edit-user?id=' . $userId);
        }
    }

    /**
     * Delete user
     */
    public function deleteUser() {
        $userId = $_GET['id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = 'User not found';
            $this->redirect($this->getBaseUrl() . '/admin/users');
            return;
        }

        $user = $this->userModel->getById($userId);

        if (!$user) {
            $_SESSION['error'] = 'User not found';
            $this->redirect($this->getBaseUrl() . '/admin/users');
            return;
        }

        if ($this->userModel->delete($userId)) {
            $_SESSION['success'] = 'User deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete user';
        }

        $this->redirect($this->getBaseUrl() . '/admin/users');
    }

    /**
     * Show user details
     */
    public function userDetails() {
        $userId = $_GET['id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = 'User not found';
            $this->redirect($this->getBaseUrl() . '/admin/users');
            return;
        }

        $user = $this->userModel->getById($userId);

        if (!$user) {
            $_SESSION['error'] = 'User not found';
            $this->redirect($this->getBaseUrl() . '/admin/users');
            return;
        }

        $roles = $this->roleModel->getAll();
        $this->loadView('admin/user/show.php', ['user' => $user, 'roles' => $roles]);
    }
}
