<?php


require_once DIR . '/../views/userview/diapoView.php';
require_once DIR . '/../models/diapoModel.php';

class diaporamaController {
    private $model;

    public function __construct() {
        $this->model = new diapoModel();
    }

    public function displayDiaporama() {
        $images = $this->model->getImages(); 
        $view = new diapoView();
        $view->display($images); 
    }
}
?>
