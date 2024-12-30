<?php
require_once __DIR__ . '/../helpers/Database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($data) {
        $connection = $this->db->connexion();
        $query = "INSERT INTO user (first_name, last_name, email, password, profile_picture, is_active) 
                    VALUES (:first_name, :last_name, :email, :password, :profile_picture, :is_active)";
        $params = [
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':profile_picture' => $data['profile_picture'],
            ':is_active' => 0 
        ];
        try {
            $this->db->request($connection, $query, $params);
            return $connection->lastInsertId();
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }
}
?>
