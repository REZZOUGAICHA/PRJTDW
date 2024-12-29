<?php
require_once __DIR__ . '/../helpers/Database.php';

class CardModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($typeId) {
        $connection = $this->db->connexion();
        $cardNumber = $this->generateCardNumber();
        $query = "INSERT INTO card (card_type_id, card_number) VALUES (:card_type_id, :card_number)";
        $params = [
            ':card_type_id' => $typeId,
            ':card_number' => $cardNumber
        ];
        try {
            $this->db->request($connection, $query, $params);
            return $connection->lastInsertId();
        } catch (Exception $e) {
            error_log("Error creating card: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

    private function generateCardNumber() {
        return sprintf('%016d', random_int(1000000000000000, 9999999999999999));
    }
}
?>
