<?php
require_once __DIR__ . '/../models/InscriptionModel.php';

class InscriptionController {
    private $inscriptionModel;

    public function __construct() {
        $this->inscriptionModel = new InscriptionModel();
    }

    public function handleInscription($data) {
        if (empty($data['first_name']) || empty($data['last_name']) || 
            empty($data['email']) || empty($data['password'])) {
            return ['error' => 'All fields are required'];
        }

        $result = $this->inscriptionModel->createUser($data);
        
        if ($result) {
            return ['success' => 'User registered successfully'];
        } else {
            return ['error' => 'Registration failed'];
        }
    }
}