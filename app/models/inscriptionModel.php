<?php
require_once __DIR__ . '/../helpers/Database.php';

class InscriptionModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createUser($data) {
        $connection = $this->db->connexion();
        
        $query = "INSERT INTO user (first_name, last_name, email, password) 
                 VALUES (:first_name, :last_name, :email, :password)";
        
        $params = [
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ];

        try {
            $result = $this->db->request($connection, $query, $params);
            return true;
        } catch (Exception $e) {
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }
}
