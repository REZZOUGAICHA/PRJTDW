
<?php
require_once __DIR__ . '/../models/MembershipModel.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';
require_once __DIR__ . '/../Views/userView/membershipView.php';
class MembershipController {
    private $membershipModel;
    private $membershipView;
    
    public function __construct() {
        $this->membershipModel = new MembershipModel();
        $this->membershipView = new MembershipView();
    }
    
    public function showMembershipForm() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $this->membershipView->displayMembershipForm();
        
    }
    
    public function submitMembership($postData, $files) {
        error_log("Starting membership submission process");
        
        // Debug session
        error_log("Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set'));
        error_log("POST data: " . print_r($postData, true));
        error_log("FILES data: " . print_r($files, true));
        
        if (!isset($_SESSION['user_id'])) {
            error_log("No user_id in session");
            return ['error' => 'Vous devez être connecté pour soumettre une demande.'];
        }
        
        if (empty($postData['card_type'])) {
            error_log("No card_type selected");
            return ['error' => 'Veuillez sélectionner un type de carte.'];
        }
        
        if (empty($files['id_card']) || empty($files['receipt'])) {
            error_log("Missing required files");
            return ['error' => 'Tous les fichiers sont requis.'];
        }
        
        try {
            $cardType = $this->membershipModel->getCardTypeDetails($postData['card_type']);
            if (!$cardType) {
                error_log("Invalid card type: " . $postData['card_type']);
                return ['error' => 'Type de carte invalide.'];
            }
            
            error_log("Starting file validation");
            // Validate files
            $idCardValidation = $this->membershipModel->validateFile($files['id_card']);
            $receiptValidation = $this->membershipModel->validateFile($files['receipt']);
            
            if (isset($idCardValidation['error'])) {
                error_log("ID card validation failed: " . $idCardValidation['error']);
                return $idCardValidation;
            }
            if (isset($receiptValidation['error'])) {
                error_log("Receipt validation failed: " . $receiptValidation['error']);
                return $receiptValidation;
            }
            
            error_log("All validations passed, proceeding with submission");
            
            // Submit application
            $result = $this->membershipModel->submitMembershipApplication(
                $_SESSION['user_id'],
                $postData['card_type'],
                $files['id_card'],
                $files['receipt'],
                $cardType['annual_fee']
            );
            
            error_log("Submission result: " . print_r($result, true));
            return $result;
            
        } catch (Exception $e) {
            error_log("Error in submitMembership: " . $e->getMessage());
            return ['error' => 'Une erreur est survenue lors de la soumission.'];
        }
    }
}
?>