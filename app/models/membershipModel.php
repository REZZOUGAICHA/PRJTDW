<?php
require_once __DIR__ . '/../helpers/Database.php';

    class MembershipModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getMembershipApplications(){
        $c = $this->db->connexion();
        $sql = "SELECT ma.id, ma.user_id, ma.card_type_id, ma.notes, ma.status, ma.application_date, u.first_name, u.last_name, ct.name as card_name
                FROM membership_application ma
                JOIN user u ON ma.user_id = u.id
                JOIN cardtype ct ON ma.card_type_id = ct.id
                ORDER BY ma.application_date DESC";
        $stmt = $this->db->request($c, $sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $result;
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
    public function hasPendingRequest($userId) {
    // Initialize the database connection
    $db = new Database();
    $conn = $db->connexion();

    // Define the query
    $query = "SELECT COUNT(*) as count FROM membership_application
              WHERE user_id = :user_id AND status = 'pending'";

    // Execute the query using the `request` method
    $stmt = $db->request($conn, $query, [
        ':user_id' => $userId
    ]);

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Close the connection
    $db->deconnexion();

    // Return whether there are pending requests
    return $result['count'] > 0;
}

}


?>