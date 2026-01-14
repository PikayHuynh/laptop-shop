<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/router/router.php';

try {
    $router = new Router($conn);
    $router->dispatch();
} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>Error 500</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
