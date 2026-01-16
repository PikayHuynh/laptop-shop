<?php

namespace admin;

require_once __DIR__ . '/../../core/Controller.php';

class DashboardController extends \Controller {

    public function index() {
        $this->loadView('admin/dashboard/dashboard.php');
    }
}
