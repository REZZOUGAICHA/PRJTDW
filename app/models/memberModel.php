<?php
require_once __DIR__ . '/../helpers/Database.php';

class MemberModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($userId, $cardId) {
        $connection = $this->db->connexion();
        $query = "INSERT INTO member (user_id, card_id) VALUES (:user_id, :card_id)";
        $params = [
            ':user_id' => $userId,
            ':card_id' => $cardId
        ];
        try {
            $this->db->request($connection, $query, $params);
            return true;
        } catch (Exception $e) {
            error_log("Error creating member: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }
}
?>
