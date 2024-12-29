<?php
require_once __DIR__ . '/../models/MemberModel.php';

class MemberController {
    private $model;

    public function __construct($db) {
        $this->model = new MemberModel($db);
    }

    public function upgradeUserToMember($userId) {
        return $this->model->upgradeUserToMember($userId);
    }
}

?>