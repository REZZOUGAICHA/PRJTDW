<?php
require_once('navbarModel.php');
require_once('navbarView.php');

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
