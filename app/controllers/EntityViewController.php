<?php
require_once __DIR__ . '/../models/EntityViewModel.php';
class EntityViewController {
    private $model;

    public function __construct() {
        $this->model = new EntityViewModel();
    }

    public function getEntityDetails($link, $tableName) {
        return $this->model->getEntityByLink($link, $tableName);
    }
}
?>
