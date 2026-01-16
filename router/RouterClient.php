<?php

class RouterClient {
    /**
     * Register client routes
     * @param Router $router
     */
    public static function register($router) {
        // Home page
        $router->addRoute('', 'client\HomeController', 'index');
    }
}
