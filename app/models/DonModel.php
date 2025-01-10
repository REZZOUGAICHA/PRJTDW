<?php
require_once __DIR__ . '/../helpers/Database.php';
class DonModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }

    public function createDon($data) {
        try {
            $connection = $this->db->connexion();
            $connection->beginTransaction();

            $paymentQuery = "INSERT INTO payment (user_id, payment_type_id, amount, description, status) 
                            VALUES (:user_id, :payment_type_id, :amount, :description, 'pending')";
            
            $stmt = $connection->prepare($paymentQuery);
            $stmt->execute([
                ':user_id' => $data['user_id'],
                ':payment_type_id' => 1, // Type "Don"
                ':amount' => $data['amount'],
                ':description' => $data['description']
            ]);
            
            $paymentId = $connection->lastInsertId();

            $receiptQuery = "INSERT INTO receipt (payment_id, file_path, upload_date) 
                            VALUES (:payment_id, :file_path, NOW())";
            
            $stmt = $connection->prepare($receiptQuery);
            $stmt->execute([
                ':payment_id' => $paymentId,
                ':file_path' => $data['file_path']
            ]);

            $connection->commit();
            return $paymentId;
        } catch (Exception $e) {
            $connection->rollBack();
            error_log("Erreur création don: " . $e->getMessage());
            throw new Exception('Échec de création du don');
        }
    }

    public function getDonHistory($userId) {
        try {
            $connection = $this->db->connexion();
            $query = "SELECT p.*, r.file_path FROM payment p 
                    JOIN receipt r ON p.id = r.payment_id 
                    WHERE p.user_id = :user_id AND p.payment_type_id = 1 
                    ORDER BY p.payment_date DESC";
            
            $stmt = $connection->prepare($query);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur récupération historique: " . $e->getMessage());
            return false;
        }
    }

    public function getAllDons() {
    try {
        $connection = $this->db->connexion();
        $query = "SELECT p.*, r.file_path, u.first_name, u.last_name 
                    FROM payment p 
                    LEFT JOIN receipt r ON p.id = r.payment_id 
                    LEFT JOIN user u ON p.user_id = u.id
                    WHERE p.payment_type_id = 1 
                    ORDER BY p.payment_date DESC";
        
        $stmt = $connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur récupération historique: " . $e->getMessage());
        return false;
    }
}

public function getDonById($id) {
    try {
        $connection = $this->db->connexion();
        $query = "SELECT p.*, r.file_path, u.first_name, u.last_name 
                  FROM payment p 
                  LEFT JOIN receipt r ON p.id = r.payment_id 
                  LEFT JOIN user u ON p.user_id = u.id 
                  WHERE p.id = :id AND p.payment_type_id = 1";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur récupération don: " . $e->getMessage());
        return false;
    }
}

public function updateDonStatus($id, $status) {
    try {
        $connection = $this->db->connexion();
        $query = "UPDATE payment SET status = :status WHERE id = :id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $e) {
        error_log("Erreur mise à jour statut don: " . $e->getMessage());
    }
}


}

?>