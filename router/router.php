<?php

require_once __DIR__ . '/../config/database.php';

class Router {
    private $controller;
    private $action;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->parseRequest();
    }

    private function parseRequest() {
        $this->controller = isset($_GET['controller']) ? $_GET['controller'] : 'category';
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'index';
    }

    public function dispatch() {
        $controllerName = ucfirst($this->controller) . 'Controller';
        $controllerFile = __DIR__ . '/../controller/' . $this->controller . '.controller.php';

        if (!file_exists($controllerFile)) {
            $this->error404("Controller not found");
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            $this->error404("Controller class not found");
            return;
        }

        $controller = new $controllerName($this->db);

        if (!method_exists($controller, $this->action)) {
            $this->error404("Action not found");
            return;
        }

        $result = $controller->{$this->action}();

        if (is_array($result) && isset($result['view']) && isset($result['data'])) {
            $this->render($result['view'], $result['data']);
        }
    }

    private function render($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../view/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            $this->error404("View file not found: " . $viewFile);
            return;
        }

        include $viewFile;
    }

    private function error404($message = "Page not found") {
        http_response_code(404);
        echo "<h1>Error 404</h1>";
        echo "<p>" . htmlspecialchars($message) . "</p>";
        exit;
    }
}
