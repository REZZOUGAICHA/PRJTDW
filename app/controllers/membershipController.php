<?php
require_once __DIR__ . '/../models/PaymentModel.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';


class MembershipController {
    private $paymentModel;

    public function __construct() {
        $this->paymentModel = new PaymentModel();
    }

    public function submitMembership($data, $files) {
        $fileHelper = new FileUploadHelper('uploads/');
        $db = new Database();
        $conn = $db->connexion();
        

        try {

            if ($this->hasExistingRequest($_SESSION['user_id'])) {
            return ['error' => 'Vous avez déjà une demande en cours'];
        }
        
            // Upload ID card
            $idCardResult = $fileHelper->saveFile($files['id_card'], 'id_cards/');
            if (!$idCardResult['success']) {
                return ['error' => 'Erreur lors du téléchargement de la carte d\'identité'];
            }

            // Upload receipt
            $receiptResult = $fileHelper->saveFile($files['receipt'], 'receipts/');
            if (!$receiptResult['success']) {
                return ['error' => 'Erreur lors du téléchargement du reçu'];
            }

            $conn->beginTransaction();

            // Insert payment
            $paymentId = $this->paymentModel->insertPayment($_SESSION['user_id'], $data['card_type']);

            // Insert receipt
            $this->paymentModel->insertReceipt($paymentId, $receiptResult['filePath']);

            // Insert ID card
            $this->paymentModel->insertIdCard($_SESSION['user_id'], $idCardResult['filePath']);

            $conn->commit();
            return ['success' => true];

        } catch (Exception $e) {
            $conn->rollBack();
            return ['error' => 'Une erreur est survenue : ' . $e->getMessage()];
        }
    }
    public function showMembershipForm() {
        require_once __DIR__ . '/../views/userView/membershipView.php';
        $membershipView = new membershipView();
        $membershipView->displayMembershipForm();
    }

    private function hasExistingRequest($userId) {
    $sql = "SELECT 1 FROM payment p 
            WHERE p.user_id = ? AND p.payment_type_id = 2 
            AND p.status = 'pending'";
    $result = $this->paymentModel->db->request(
        $this->paymentModel->conn, 
        $sql, 
        [$userId]
    );
    return !empty($result);
}
}
?>
