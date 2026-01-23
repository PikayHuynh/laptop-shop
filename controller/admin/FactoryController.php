<?php

namespace admin;

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../model/Factory.php';

class FactoryController extends \Controller {
    private $factoryModel;

    public function __construct() {
        $this->factoryModel = new \Factory();
    }

    /**
     * List all factories
     */
    public function listFactories() {
        $factories = $this->factoryModel->getAll();
        $this->loadView('admin/factory/list.php', ['factories' => $factories]);
    }

     /**
     * Add factories
     */
    public function addFactory() {
        $this->loadView('admin/factory/add.php');

    }

    /**
     * Create a new factory
     */
    public function createFactory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->getBaseUrl() . '/admin/create-factory');
            return;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];

        // Validate input
        if (empty($data['name']) || empty($data['description'])) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect($this->getBaseUrl() . '/admin/add-factory');
            return;
        }

        // Check if name already exists
        if ($this->factoryModel->getByName($data['name'])) {
            $_SESSION['error'] = 'Name already exists';
            $this->redirect($this->getBaseUrl() . '/admin/add-factory');
            return;
        }

        // Create factory
        if ($this->factoryModel->create($data)) {
            $_SESSION['success'] = 'Factory created successfully';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
        } else {
            $_SESSION['error'] = 'Failed to create factory';
            $this->redirect($this->getBaseUrl() . '/admin/add-factory');
        }
    }

    /**
     * Show edit factory form
     */
    public function editFactory() {
        $userId = $_GET['id'] ?? null;
        
        if (!$userId) {
            $_SESSION['error'] = 'Factory not found';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
            return;
        }

        $factory = $this->factoryModel->getById($userId);
        
        if (!$factory) {
            $_SESSION['error'] = 'Factory not found';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
            return;
        }

        $roles = $this->roleModel->getAll();
        $this->loadView('admin/factory/edit.php', ['factory' => $factory, 'roles' => $roles]);
    }

    /**
     * Update factory
     */
    public function updateFactory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->getBaseUrl() . '/admin/factories');
            return;
        }

        $userId = $_POST['user_id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = 'Factory not found';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
            return;
        }

        $factory = $this->factoryModel->getById($userId);

        if (!$factory) {
            $_SESSION['error'] = 'Factory not found';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
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
            $this->redirect($this->getBaseUrl() . '/admin/edit-factory?id=' . $userId);
            return;
        }

        // Check if new email already exists (different from current email)
        if ($data['email'] !== $factory['email'] && $this->factoryModel->getByEmail($data['email'])) {
            $_SESSION['error'] = 'Email already exists';
            $this->redirect($this->getBaseUrl() . '/admin/edit-factory?id=' . $userId);
            return;
        }

        // Handle password update
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        // Update factory
        if ($this->factoryModel->update($userId, $data)) {
            $_SESSION['success'] = 'Factory updated successfully';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
        } else {
            $_SESSION['error'] = 'Failed to update factory';
            $this->redirect($this->getBaseUrl() . '/admin/edit-factory?id=' . $userId);
        }
    }

    /**
     * Delete factory
     */
    public function deleteFactory() {
        $userId = $_GET['id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = 'Factory not found';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
            return;
        }

        $factory = $this->factoryModel->getById($userId);

        if (!$factory) {
            $_SESSION['error'] = 'Factory not found';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
            return;
        }

        if ($this->factoryModel->delete($userId)) {
            $_SESSION['success'] = 'Factory deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete factory';
        }

        $this->redirect($this->getBaseUrl() . '/admin/factories');
    }

    /**
     * Show factory details
     */
    public function showFactory() {
        $factoryId = $_GET['id'] ?? null;

        if (!$factoryId) {
            $_SESSION['error'] = 'Factory not found';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
            return;
        }

        $factory = $this->factoryModel->getById($factoryId);

        if (!$factory) {
            $_SESSION['error'] = 'Factory not found';
            $this->redirect($this->getBaseUrl() . '/admin/factories');
            return;
        }

        $this->loadView('admin/factory/show.php', ['factory' => $factory]);
    }
}
