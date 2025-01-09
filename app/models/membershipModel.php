
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../Helpers/Database.php';

// MembershipModel.php
class MembershipModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connexion();
    }

    public function submitMembershipApplication($userId, $cardTypeId, $idCardFile, $receiptFile, $amount) {
        try {
            error_log("Starting submitMembershipApplication transaction");
            $this->conn->beginTransaction();
            
            // 1. Store files
            error_log("Storing files");
            $idCardPath = $this->storeFile($idCardFile, 'id_cards');
            $receiptPath = $this->storeFile($receiptFile, 'receipts');
            error_log("Files stored successfully - ID Card: $idCardPath, Receipt: $receiptPath");
            
            // 2. Create payment record
            error_log("Creating payment record");
            $sql = "INSERT INTO payment (user_id, payment_type_id, amount, status, description) 
                    VALUES (?, 2, ?, 'pending', 'Payment for membership card')";
            $this->db->request($this->conn, $sql, [$userId, $amount]);
            $paymentId = $this->conn->lastInsertId();
            error_log("Payment created with ID: $paymentId");
            
            // 3. Store receipt record
            error_log("Storing receipt record");
            $sql = "INSERT INTO receipt (payment_id, file_path) VALUES (?, ?)";
            $this->db->request($this->conn, $sql, [$paymentId, $receiptPath]);
            
            // 4. Store ID card record
            error_log("Storing ID card record");
            $sql = "INSERT INTO id_card (user_id, file_path) VALUES (?, ?)";
            $this->db->request($this->conn, $sql, [$userId, $idCardPath]);
            
            // 5. Create membership application - EXPLICIT ERROR HANDLING
            error_log("Creating membership application");
            try {
                $sql = "INSERT INTO membership_application (
                    user_id, card_type_id, status, application_date
                ) VALUES (?, ?, 'pending', NOW())";
                $stmt = $this->conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Failed to prepare membership application statement: " . print_r($this->conn->errorInfo(), true));
                }
                $result = $stmt->execute([$userId, $cardTypeId]);
                if (!$result) {
                    throw new Exception("Failed to execute membership application insert: " . print_r($stmt->errorInfo(), true));
                }
                error_log("Membership application created successfully");
            } catch (Exception $e) {
                error_log("Error creating membership application: " . $e->getMessage());
                throw $e;
            }
            
            // 6. Update user status
            error_log("Updating user status");
            $sql = "UPDATE user SET membership_status = 'pending' WHERE id = ?";
            $this->db->request($this->conn, $sql, [$userId]);
            
            $this->conn->commit();
            error_log("Transaction committed successfully");
            return ['success' => true];
            
        } catch (Exception $e) {
            error_log("Error in submitMembershipApplication: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $this->conn->rollBack();
            return ['error' => 'Une erreur est survenue lors de la soumission. Veuillez réessayer.'];
        }
    }
}
?>