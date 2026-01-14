<?php

namespace client;

require_once __DIR__ . '/../../core/Controller.php';

class HomeController extends \Controller {
    public function index() {
        $this->loadView('client/home/home.php');
    }
}
