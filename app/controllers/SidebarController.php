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
// a modifier, il ya un parametre ici 
        public function showSidebar() {
        require_once __DIR__ . '/../Views/adminView/sidebarView.php';
        $view = new SidebarView();
        $view->displaySidebar();
}}
?>
