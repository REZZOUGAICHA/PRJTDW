<?php
require_once __DIR__ . '/../models/DonModel.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';
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
    $dons = $this->getAllDons();
    require_once __DIR__ . '/../views/adminView/DonView.php';
    $view = new DonView();
    $view->displayDons($dons);
}

public function showDonDetails($id) {
    $don = $this->model->getDonById($id);
    require_once __DIR__ . '/../views/adminView/DonDetailsView.php';
    $view = new DonDetailsView();
    $view->displayDonDetails($don);
}

public function acceptDon($id) {
    $this->model->updateDonStatus($id, 'approved');
    header('Location: ' . BASE_URL . '/admin/dons');
    exit;
}

public function refuseDon($id) {
    $this->model->updateDonStatus($id, 'rejected');
    header('Location: ' . BASE_URL . '/admin/dons');
    exit;
}


//-------------------history-------------------------------------
public function getDonHistory($userId) {
        try {
            // Fetch donation history from the model
            $donHistory = $this->model->getDonHistory($userId);

            // Format the data for the view
            return array_map(function($don) {
                return [
                    'status' => $don['status'] ?? 'pending',
                    'id' => $don['id'] ?? null,
                    'amount' => $don['amount'] ?? null,
                    'payment_date' => $don['payment_date'] ?? null,
                    'file_path' => $don['file_path'] ?? null,
                ];
            }, $donHistory);
        } catch (Exception $e) {
            error_log("Erreur récupération historique des dons: " . $e->getMessage());
            return ['error' => 'Une erreur est survenue lors de la récupération de l\'historique des dons.'];
        }
    }


public function showUserDonHistory($userId) {
    $donHistory = $this->getDonHistory($userId);
    require_once __DIR__ . '/../views/userView/HistoriqueView.php';
    $view = new HistoriqueView();
    $view->displayDonHistory($donHistory);
}


}


?>