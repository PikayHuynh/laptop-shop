<?php

namespace admin;

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../model/Category.php';

class CategoryController extends \Controller {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new \Category();
    }

    /**
     * List all categories
     */
    public function listCategories() {
        $categories = $this->categoryModel->getAll();
        $this->loadView('admin/category/list.php', ['categories' => $categories]);
    }

    /**
     * Show add role form
     */
    public function addCategory() {
        $this->loadView('admin/category/add.php');
    }

    /**
     * Create a new role
     */
    public function createCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->getBaseUrl() . '/admin/add-category');
            return;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];

        // Validate input
        if (empty($data['name'])) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect($this->getBaseUrl() . '/admin/add-category');
            return;
        }

        // Check if email already exists
        if ($this->categoryModel->getByName($data['name'])) {
            $_SESSION['error'] = 'Name already exists';
            $this->redirect($this->getBaseUrl() . '/admin/add-category');
            return;
        }

        // Create category
        if ($this->categoryModel->create($data)) {
            $_SESSION['success'] = 'Category created successfully';
            $this->redirect($this->getBaseUrl() . '/admin/categories');
        } else {
            $_SESSION['error'] = 'Failed to create category';
            $this->redirect($this->getBaseUrl() . '/admin/add-category');
        }
    }

    /**
     * Show edit role form
     */
    public function editCategory() {
        $categoryId = $_GET['id'] ?? null;
        
        if (!$categoryId) {
            $_SESSION['error'] = 'Category not found';
            $this->redirect($this->getBaseUrl() . '/admin/categories');
            return;
        }

        $category = $this->categoryModel->getById($categoryId);
        
        if (!$category) {
            $_SESSION['error'] = 'Category not found';
            $this->redirect($this->getBaseUrl() . '/admin/categories');
            return;
        }

        $this->loadView('admin/category/edit.php', ["category" => $category]);
    }

    /**
     * Update role
     */
    public function updateCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->getBaseUrl() . '/admin/categories');
            return;
        }

        $categoryId = $_POST['category_id'] ?? null;

        if (!$categoryId) {
            $_SESSION['error'] = 'role not found';
            $this->redirect($this->getBaseUrl() . '/admin/categories');
            return;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validate input
        if (empty($data['name'])) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect($this->getBaseUrl() . '/admin/edit-category?id=' . $categoryId);
            return;
        }

        // Update role
        if ($this->categoryModel->update($categoryId, $data)) {
            $_SESSION['success'] = 'Category updated successfully';
            $this->redirect($this->getBaseUrl() . '/admin/categories');
        } else {
            $_SESSION['error'] = 'Failed to update role';
            $this->redirect($this->getBaseUrl() . '/admin/edit-category?id=' . $categoryId);
        }
    }

    /**
     * Delete role
     */
    public function deleteCategory() {
        $categoryId = $_GET['id'] ?? null;

        if (!$categoryId) {
            $_SESSION['error'] = 'category not found';
            $this->redirect($this->getBaseUrl() . '/admin/categories');
            return;
        }

        if ($this->categoryModel->delete($categoryId)) {
            $_SESSION['success'] = 'Category deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete role';
        }

        $this->redirect($this->getBaseUrl() . '/admin/categories');
    }

    /**
     * Show role details
     */
    public function showCategory() {
        $categoryId = $_GET['id'] ?? null;

        if (!$categoryId) {
            $_SESSION['error'] = 'Category not found';
            $this->redirect($this->getBaseUrl() . '/admin/categories');
            return;
        }

        $category = $this->categoryModel->getById($categoryId);

        if (!$category) {
            $_SESSION['error'] = 'Category not found';
            $this->redirect($this->getBaseUrl() . '/admin/categories');
            return;
        }

        $this->loadView('admin/category/show.php', ['category' => $category]);
    }
}
