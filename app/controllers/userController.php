<?php 

require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $model;

    public function __construct($db) {
        $this->model = new UserModel($db);
    }

    public function registerUser($data) {
        return $this->model->createUser($data);
    }

    public function getUser($id) {
        return $this->model->getUserById($id);
    }
}

?>