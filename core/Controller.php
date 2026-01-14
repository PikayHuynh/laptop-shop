<?php

// Include config
require_once __DIR__ . '/../config/config.php';

class Controller {
    /**
     * Get the base URL for the application
     * @return string Base URL
     */
    protected function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($script);
        
        // Handle base path for subdirectory installation
        if (strpos($basePath, '/laptop_shop') !== false) {
            $basePath = '/laptop_shop';
        } else {
            $basePath = '';
        }
        
        return $protocol . '://' . $host . $basePath;
    }
    
    /**
     * Load a view file
     * @param string $viewPath Path to the view file relative to view directory
     * @param array $data Optional data to pass to the view
     */
    protected function loadView($viewPath, $data = []) {
        // Extract data array to variables
        extract($data);
        
        // Make base URL available to views
        $baseUrl = $this->getBaseUrl();
        
        // Build full path to view file
        $viewFile = __DIR__ . '/../view/' . $viewPath;
        
        // Check if view file exists
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . $viewPath);
        }
    }
    
    /**
     * Redirect to a URL
     * @param string $url URL to redirect to
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}
