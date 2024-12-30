<?php

require_once __DIR__ . '/../models/sidebarModel.php';

class SidebarController {
    private $model;

    public function __construct() {
        $this->model = new SidebarModel();
    }

    public function getSidebarData() {
        return $this->model->getSidebarItems();
    }
}
?>
