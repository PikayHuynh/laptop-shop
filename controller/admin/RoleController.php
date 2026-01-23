<?php

namespace admin;

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../model/Role.php';
require_once __DIR__ . '/../../model/User.php';

class RoleController extends \Controller {
    private $roleModel;
    private $userModel;

    public function __construct() {
        $this->roleModel = new \Role();
        $this->userModel = new \User();
    }

    /**
     * List all roles
     */
    public function listRoles() {
        $roles = $this->roleModel->getAll();
        $this->loadView('admin/role/list.php', ['roles' => $roles]);
    }

    /**
     * Show add role form
     */
    public function addRole() {
        $this->loadView('admin/role/add.php');
    }

    /**
     * Create a new role
     */
    public function createRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->getBaseUrl() . '/admin/add-role');
            return;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
        ];

        // Validate input
        if (empty($data['name'])) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect($this->getBaseUrl() . '/admin/add-role');
            return;
        }

        // Check if email already exists
        if ($this->roleModel->getByName($data['name'])) {
            $_SESSION['error'] = 'Name already exists';
            $this->redirect($this->getBaseUrl() . '/admin/add-role');
            return;
        }

        // Create role
        if ($this->roleModel->create($data)) {
            $_SESSION['success'] = 'Role created successfully';
            $this->redirect($this->getBaseUrl() . '/admin/roles');
        } else {
            $_SESSION['error'] = 'Failed to create role';
            $this->redirect($this->getBaseUrl() . '/admin/add-role');
        }
    }

    /**
     * Show edit role form
     */
    public function editRole() {
        $roleId = $_GET['id'] ?? null;
        
        if (!$roleId) {
            $_SESSION['error'] = 'Role not found';
            $this->redirect($this->getBaseUrl() . '/admin/roles');
            return;
        }

        $role = $this->roleModel->getById($roleId);
        
        if (!$role) {
            $_SESSION['error'] = 'Role not found';
            $this->redirect($this->getBaseUrl() . '/admin/roles');
            return;
        }

        $this->loadView('admin/role/edit.php', ["role" => $role]);
    }

    /**
     * Update role
     */
    public function updateRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->getBaseUrl() . '/admin/roles');
            return;
        }

        $roleId = $_POST['role_id'] ?? null;

        if (!$roleId) {
            $_SESSION['error'] = 'role not found';
            $this->redirect($this->getBaseUrl() . '/admin/roles');
            return;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
        ];

        // Validate input
        if (empty($data['name'])) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect($this->getBaseUrl() . '/admin/edit-role?id=' . $roleId);
            return;
        }

        // Update role
        if ($this->roleModel->update($roleId, $data)) {
            $_SESSION['success'] = 'Role updated successfully';
            $this->redirect($this->getBaseUrl() . '/admin/roles');
        } else {
            $_SESSION['error'] = 'Failed to update role';
            $this->redirect($this->getBaseUrl() . '/admin/edit-role?id=' . $roleId);
        }
    }

    /**
     * Delete role
     */
    public function deleteRole() {
        $roleId = $_GET['id'] ?? null;

        if (!$roleId) {
            $_SESSION['error'] = 'role not found';
            $this->redirect($this->getBaseUrl() . '/admin/roles');
            return;
        }
        
        $role = $this->roleModel->getById($roleId);

        if (!$role) {
            $_SESSION['error'] = 'role not found';
            $this->redirect($this->getBaseUrl() . '/admin/roles');
            return;
        }

        $users = $this->userModel->getByRoleName($role['name']);

        if (!empty($users)) {
            $deletedUsers = $this->userModel->deleteAll($users);

            if (!$deletedUsers) {
                $_SESSION['error'] = 'Failed to delete users of this role';
                $this->redirect($this->getBaseUrl() . '/admin/roles');
                return;
            }
        }




        if ($this->roleModel->delete($roleId)) {
            $_SESSION['success'] = 'Role deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete role';
        }

        $this->redirect($this->getBaseUrl() . '/admin/roles');
    }

    /**
     * Show role details
     */
    public function showRole() {
        $roleId = $_GET['id'] ?? null;

        if (!$roleId) {
            $_SESSION['error'] = 'Role not found';
            $this->redirect($this->getBaseUrl() . '/admin/roles');
            return;
        }

        $role = $this->roleModel->getById($roleId);

        if (!$role) {
            $_SESSION['error'] = 'Role not found';
            $this->redirect($this->getBaseUrl() . '/admin/roles');
            return;
        }

        $this->loadView('admin/role/show.php', ['role' => $role]);
    }
}
