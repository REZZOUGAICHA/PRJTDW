<?php
require_once __DIR__ . '/../models/DonModel.php';
class DonController {
    
    private $model;
    private $fileUploader;

    public function __construct() {
        $this->model = new DonModel();
        $this->fileUploader = new FileUploadHelper('uploads/receipts/');
    }
    public function getAllDons() {
    $dons = $this->model->getAllDons();

    // Formater les données pour la vue
    return array_map(function($don) {
        return [
            'id' => $don['id'] ?? null,
            'user' => ($don['first_name'] ?? '') . ' ' . ($don['last_name'] ?? ''),
            'amount' => $don['amount'] ?? null,
            'payment_date' => $don['payment_date'] ?? null,
            'description' => $don['description'] ?? '',
            'status' => $don['status'] ?? 'pending',
            'file_path' => $don['file_path'] ?? null,
        ];
    }, $dons);
}


    public function handleDonSubmission() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        try {
            if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Veuillez fournir un reçu valide');
            }

            $fileResult = $this->fileUploader->saveFile($_FILES['receipt']);
            if (!$fileResult['success']) {
                throw new Exception($fileResult['error']);
            }

            $donData = [
                'user_id' => $_SESSION['user_id'],
                'amount' => filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT),
                'description' => strip_tags($_POST['description']),
                'file_path' => $fileResult['filePath']
            ];

            if (!$donData['amount']) {
                throw new Exception('Montant invalide');
            }

            $this->model->createDon($donData);
            $_SESSION['success'] = 'Don effectué avec succès';
            header('Location: ' . BASE_URL . '/don');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/don');
            exit;
        }
    }

    public function showDonForm() {
        require_once __DIR__ . '/../views/userView/DonView.php';
        $view = new DonFormView();
        $view->display();
    }
    public function showDonsForAdmin() {
        require_once __DIR__ . '/../views/adminView/DonView.php';
        $view = new DonView();
        $view->displayDons();
    }
}


?>