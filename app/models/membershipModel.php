<?php
require_once __DIR__ . '/../helpers/Database.php';

    class MembershipModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getcards() {
        $c = $this->db->connexion();
        $sql = "SELECT * FROM cardtype";
        $stmt = $this->db->request($c, $sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $result;
    }

    public function createMembershipApplication($data) { 
        try {
            $connection = $this->db->connexion();
            $connection->beginTransaction();

            // Étape 1 : Créer un paiement
            $paymentQuery = "INSERT INTO payment (user_id, payment_type_id, amount, description, status) 
           VALUES (:user_id, :payment_type_id, :amount, :description, 'pending')";
            
            $stmt = $this->db->request($connection, $paymentQuery, [
                ':user_id' => $data['user_id'],
                ':payment_type_id' => 2, // Type "Carte"
                ':amount' => $data['amount'],
                ':description' => $data['description']
            ]);
            
            $paymentId = $connection->lastInsertId();

            // Étape 2 : Associer le reçu au paiement
            $receiptQuery = "INSERT INTO receipt (payment_id, file_path, upload_date) 
                             VALUES (:payment_id, :file_path, NOW())";
            
            $this->db->request($connection, $receiptQuery, [
                ':payment_id' => $paymentId,
                ':file_path' => $data['receipt_file_path']
            ]);

            // Étape 3 : Associer la carte d'identité à l'utilisateur
            $idCardQuery = "INSERT INTO id_card (user_id, file_path, upload_date) 
                            VALUES (:user_id, :file_path, NOW())";
            
            $this->db->request($connection, $idCardQuery, [
                ':user_id' => $data['user_id'],
                ':file_path' => $data['id_card_file_path']
            ]);

            // Étape 4 : Créer une application de membership
            $applicationQuery = "INSERT INTO membership_application (user_id, card_type_id, notes, status, application_date) 
                                 VALUES (:user_id, :card_type_id, :notes, 'pending', NOW())";
            
            $this->db->request($connection, $applicationQuery, [
                ':user_id' => $data['user_id'],
                ':card_type_id' => $data['card_type_id'],
                ':notes' => $data['notes']
            ]);

            $connection->commit();
            return $connection->lastInsertId();
        } catch (Exception $e) {
            $connection->rollBack();
            error_log("Erreur création application de membership: " . $e->getMessage());
            throw new Exception('Échec de création de l\'application de membership');
        }
    }
}


?>