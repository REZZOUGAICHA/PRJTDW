<?php 
require_once __DIR__ . '/../models/topbarModel.php';


class TopbarController {
    private $topbarModel;
    

    public function __construct() {
        $this->topbarModel = new TopbarModel();
        
    }

    public function getTopbarData() {
        return $this->topbarModel->getTopbarData();
    }

    public function getSocialMediaLinks() {
        return $this->topbarModel->getSocialMediaLinks();
    }
}
?>