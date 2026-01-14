<?php

// Base URL configuration
define('BASE_URL', '/laptop_shop/');

// Helper function to get asset URL
function asset_url($path) {
    // Remove leading slash if present
    $path = ltrim($path, '/');
    return BASE_URL . 'view/resource/' . $path;
}
