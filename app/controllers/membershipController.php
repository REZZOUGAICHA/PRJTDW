<?php
require_once __DIR__ . '/../models/MembershipModel.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class MembershipController {
    private $model;
    private $fileUploader;
    private $userId;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            throw new Exception('User not logged in');
        }
        $this->userId = $_SESSION['user_id'];
        $this->model = new MembershipModel();
        $this->fileUploader = new FileUploadHelper('uploads/memberships/');
    }
    

    public function handleMembershipApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        try {
            // Validate and upload receipt file
            if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Veuillez fournir un reçu valide');
            }
            $receiptResult = $this->fileUploader->saveFile($_FILES['receipt']);
            if (!$receiptResult['success']) {
                throw new Exception($receiptResult['error']);
            }

            // Validate and upload ID card file
            if (!isset($_FILES['id_card']) || $_FILES['id_card']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Veuillez fournir une carte d\'identité valide');
            }
            $idCardResult = $this->fileUploader->saveFile($_FILES['id_card']);
            if (!$idCardResult['success']) {
                throw new Exception($idCardResult['error']);
            }

            $applicationData = [
                'user_id' => $_SESSION['user_id'], // Use session directly like in Don
                'amount' => filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT),
                'description' => strip_tags($_POST['description']),
                'notes' => strip_tags($_POST['notes'] ?? ''),
                'card_type_id' => filter_var($_POST['card_type_id'], FILTER_VALIDATE_INT),
                'receipt_file_path' => $receiptResult['filePath'],
                'id_card_file_path' => $idCardResult['filePath']
            ];

            if (!$applicationData['amount']) {
                throw new Exception('Montant invalide');
            }
            if (!$applicationData['card_type_id']) {
                throw new Exception('Type de carte invalide');
            }

            $result = $this->model->createMembershipApplication($applicationData);
            $_SESSION['success'] = 'Demande de membership soumise avec succès';
            header('Location: ' . BASE_URL . '/membership');
            exit;
        } catch (Exception $e) {
            error_log('Membership Application Error: ' . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/membership');
            exit;
        }
    }



    public function showMembershipForm() {
        require_once __DIR__ . '/../views/userView/MembershipView.php';
        $view = new MembershipView();
        $cards = $this->model->getCards();
        $hasPendingRequest = $this->model->hasPendingRequest($this->userId);
        $view->display($cards, $hasPendingRequest);
    }

    public function showMembershipApplications() {
        // Fetch data from the model
        $applications = $this->model->getMembershipApplications();
        require_once __DIR__ . '/../views/adminView/AdhesionsView.php';
        // Pass data to the view
        $view = new MembershipTableView();
        $view->display($applications);
    }
}

?>
