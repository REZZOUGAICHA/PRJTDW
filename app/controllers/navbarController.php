<?php
require_once DIR . '/../views/userView/navbarView.php';
require_once DIR . '/../models/navbarModel.php';

class MenuController {
    private $model;

    public function __construct() {
        $this->model = new MenuModel();
    }

    public function getMenu() {
        $menuItems = $this->model->getMenuItems();
        $view = new MenuView();
        $view->display($menuItems);
    }
}
?>
