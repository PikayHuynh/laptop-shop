<?php

namespace admin;

require_once __DIR__ . '/../../core/Controller.php';

class AdminController extends \Controller {

    public function index() {
        $this->loadView('admin/dashboard/dashboard.php');
    }
}
