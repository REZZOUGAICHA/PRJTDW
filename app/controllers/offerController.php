<?php
require_once __DIR__ . '/../models/offerModel.php';
class offerController {
private $model;

public function __construct() {
    $this->model = new offerModel();
}

public function getOffersData() {
    return $this->model->getOffers();
}
}
?>