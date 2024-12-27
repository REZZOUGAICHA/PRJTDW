<?php

require_once __DIR__ . '/../models/navbarModel.php';

class MenuController {
    private $model;

    public function __construct() {
        $this->model = new MenuModel();
    }

    public function getMenu() {
        return $this->model->getMenuItems();  
}
}