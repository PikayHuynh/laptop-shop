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
        $router->addRoute('admin/show-user','admin\UserController', 'userDetails');
        $router->addRoute('admin/add-user', 'admin\UserController', 'addUser');
        $router->addRoute('admin/create-user', 'admin\UserController', 'createUser');
        $router->addRoute('admin/edit-user', 'admin\UserController', 'editUser');
        $router->addRoute('admin/update-user', 'admin\UserController', 'updateUser');
        $router->addRoute('admin/delete-user', 'admin\UserController', 'deleteUser');


        // Factory management routes
        $router->addRoute('admin/factories', 'admin\FactoryController', 'listFactories');
        $router->addRoute('admin/show-factory', 'admin\FactoryController', 'showFactory');
        $router->addRoute('admin/add-factory', 'admin\FactoryController', 'addFactory');
        $router->addRoute('admin/create-factory', 'admin\FactoryController', 'createFactory');
        $router->addRoute('admin/edit-factory', 'admin\FactoryController', 'editFactory');
        $router->addRoute('admin/update-factory', 'admin\FactoryController', 'updateFactory');
        $router->addRoute('admin/delete-factory', 'admin\FactoryController', 'deleteFactory');


        // Role management routes
        $router->addRoute('admin/roles', 'admin\RoleController', 'listRoles');
        $router->addRoute('admin/show-role', 'admin\RoleController', 'showRole');
        $router->addRoute('admin/add-role', 'admin\RoleController', 'addRole');
        $router->addRoute('admin/create-role', 'admin\RoleController', 'createRole');
        $router->addRoute('admin/edit-role', 'admin\RoleController', 'editRole');
        $router->addRoute('admin/update-role', 'admin\RoleController', 'updateRole');
        $router->addRoute('admin/delete-role', 'admin\RoleController', 'deleteRole');
    }
}
