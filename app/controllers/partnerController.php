<?php
require_once __DIR__ . '/../models/partnerModel.php';

class partnerController {
    private $partnerModel;

    public function __construct() {
        $this->partnerModel = new partnerModel();
    }

    public function getPartnersByCategory() {
        return $this->partnerModel->getPartnersByCategory();
    }
}
?>
