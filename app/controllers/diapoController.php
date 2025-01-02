<?php
require_once __DIR__ . '/../models/diapoModel.php';




class DiaporamaController {
    private $model;


    public function __construct() {
        $this->model = new DiapoModel();
    }

    public function getDiaporama() {
        return $this->model->getImages();
    }

    public function showDiaporama() {
        require_once __DIR__ . '/../Views/userView/LandingView.php';
        $view = new LandingView();
        $view->diaporamaView();
    }




}
?>