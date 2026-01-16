<?php

class RouterAdmin {
    /**
     * Register admin routes
     * @param Router $router
     */
    public static function register($router) {
        // Admin dashboard
        $router->addRoute('admin', 'admin\DashboardController', 'index');
        
        // User management routes
        $router->addRoute('admin/users', 'admin\UserController', 'listUsers');
        $router->addRoute('admin/add-user', 'admin\UserController', 'addUser');
        $router->addRoute('admin/create-user', 'admin\UserController', 'createUser');
        $router->addRoute('admin/edit-user', 'admin\UserController', 'editUser');
        $router->addRoute('admin/update-user', 'admin\UserController', 'updateUser');
        $router->addRoute('admin/delete-user', 'admin\UserController', 'deleteUser');
    }
}
