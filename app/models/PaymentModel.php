<?php
require_once __DIR__ . '/../helpers/Database.php';

class PaymentModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connexion();
    }

    public function insertPayment($userId, $cardTypeId) {
        $sql = "INSERT INTO payment (user_id, payment_type_id, amount) 
                SELECT :user_id, 2, ct.annual_fee 
                FROM cardtype ct WHERE ct.id = :card_type";
        $params = [':user_id' => $userId, ':card_type' => $cardTypeId];
        $this->db->request($this->conn, $sql, $params);
        return $this->conn->lastInsertId();
    }

    public function insertReceipt($paymentId, $filePath) {
        $sql = "INSERT INTO receipt (payment_id, file_path) VALUES (:payment_id, :file_path)";
        $params = [
            ':payment_id' => $paymentId,
            ':file_path' => $filePath
        ];
        $this->db->request($this->conn, $sql, $params);
    }

    public function insertIdCard($userId, $filePath) {
        $sql = "INSERT INTO id_card (user_id, file_path) VALUES (:user_id, :file_path)";
        $params = [
            ':user_id' => $userId,
            ':file_path' => $filePath
        ];
        $this->db->request($this->conn, $sql, $params);
    }
}
?>
