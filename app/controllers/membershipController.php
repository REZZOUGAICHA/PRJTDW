<?php
require_once __DIR__ . '/../models/MembershipModel.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';
require_once __DIR__ . '/../views/AdminView/AdhesionsDetailsView.php';

class MembershipController {
    private $model;
    private $fileUploader;
    private $userId;
    private $isAdminContext;

    public function __construct() {
        $this->isAdminContext = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
        
        // Only enforce user login check for non-admin routes
        if (!$this->isAdminContext && !isset($_SESSION['user_id'])) {
            throw new Exception('User not logged in');
        }
        
        $this->userId = $_SESSION['user_id'] ?? null;
        
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

        // Get card type ID and validate
        $cardTypeId = filter_var($_POST['card_type_id'], FILTER_VALIDATE_INT);
        if (!$cardTypeId) {
            throw new Exception('Type de carte invalide');
        }

        // Get the card details to get the annual fee
        $cards = $this->model->getCards();
        $selectedCard = null;
        foreach ($cards as $card) {
            if ($card['id'] == $cardTypeId) {
                $selectedCard = $card;
                break;
            }
        }

        if (!$selectedCard) {
            throw new Exception('Type de carte non trouvé');
        }

        $applicationData = [
            'user_id' => $_SESSION['user_id'],
            'amount' => $selectedCard['annual_fee'],
            'description' => 'Paiement de la cotisation annuelle - ' . $selectedCard['name'],
            'notes' => strip_tags($_POST['notes'] ?? ''),
            'card_type_id' => $cardTypeId,
            'receipt_file_path' => $receiptResult['filePath'],
            'id_card_file_path' => $idCardResult['filePath']
        ];

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
    public function getRequestById($id) {
        return $this->model->getMembershipApplicationById($id);
    }

    

    public function showMembershipDetails($id) {
        $adhesionsDetailsView = new AdhesionsDetailsView();
        $adhesionsDetailsView->displayMembershipDetail($id);
    }

    public function acceptRequest($id) {
        $this->model->acceptMembershipApplication($id);
        header('Location: ' . BASE_URL . '/admin/adhesions');
        exit;
    }

    public function refuseRequest($id) {
        $this->model->refuseMembershipApplication($id);
        header('Location: ' . BASE_URL . '/admin/adhesions');
        exit;
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
