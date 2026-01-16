<?php

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include router
require_once __DIR__ . '/core/router.php';

// Include route files
require_once __DIR__ . '/router/RouterAdmin.php';
require_once __DIR__ . '/router/RouterClient.php';

// Create router instance
$router = new Router();

// Register routes
RouterAdmin::register($router);
RouterClient::register($router);

// Dispatch the request
$router->dispatch();

?>
