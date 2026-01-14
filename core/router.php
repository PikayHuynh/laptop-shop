<?php

require_once __DIR__ . '/Controller.php';

class Router {
    private $routes = [];
    
    /**
     * Add a route to the router
     * @param string $path URL path
     * @param string $controller Controller class name
     * @param string $action Method name in controller
     */
    public function addRoute($path, $controller, $action = 'index') {
        $this->routes[$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    /**
     * Dispatch the request to the appropriate controller
     */
    public function dispatch() {
        // Get the request URI
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string if present
        $uri = strtok($uri, '?');
        
        // Remove base path (/laptop_shop/)
        $basePath = '/laptop_shop';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
            // Remove leading slash if present
            $uri = ltrim($uri, '/');
        }
        
        // Remove leading and trailing slashes
        $uri = trim($uri, '/');
        
        // Default to empty string for root
        if (empty($uri)) {
            $uri = '';
        }
        
        // Check if route exists
        if (isset($this->routes[$uri])) {
            $route = $this->routes[$uri];
            $controllerName = $route['controller'];
            $action = $route['action'];
            
            // Check if controller file exists
            $controllerFile = __DIR__ . '/../controller/' . str_replace('\\', '/', $controllerName) . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                // Use fully qualified class name for namespaced controllers
                $fullControllerName = '\\' . $controllerName;
                
                // Create controller instance
                $controller = new $fullControllerName();
                
                // Check if action method exists
                if (method_exists($controller, $action)) {
                    // Call the action method
                    $controller->$action();
                } else {
                    die("Action '{$action}' not found in controller '{$controllerName}'");
                }
            } else {
                die("Controller file not found: {$controllerFile}");
            }
        } else {
            // Route not found - show 404 or redirect to home
            http_response_code(404);
            die("Page not found. URI: '{$uri}'. Available routes: " . implode(', ', array_keys($this->routes)));
        }
    }
}
