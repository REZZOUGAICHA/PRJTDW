<?php
require_once('diapoModel.php');
require_once('diapoView.php');

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
