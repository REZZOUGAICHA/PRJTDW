<?php
require_once __DIR__ . '/../models/discountModel.php';
require_once __DIR__ . '/../Views/userView/offerView.php';
class DiscountController {
private $model;

public function __construct() {
    $this->model = new DiscountModel();
}

public function getDiscountsData() {
    return $this->model->getDiscounts();
}

public function showDiscount() {
    $view = new offerView();
    $view->displaydiscount();
}
}
?>