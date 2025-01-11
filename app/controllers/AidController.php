<?php

require_once __DIR__ . '/../models/AidModel.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';


class AidController {
    private $model;
    private $fileUploadHelper;

    public function __construct() {
        $this->model = new AidModel();
         $this->fileUploadHelper = new FileUploadHelper('uploads/aid/');
    }
    public function showAidTypesWithFiles() {
        return $this->model->getAidTypesWithFiles();
    }
    public function showAidRequestForm() {
    require_once __DIR__ . '/../views/userView/AidView.php';
    // Fetch aid types and files (you can replace this with your actual logic)
    $aidTypes = $this->showAidTypesWithFiles();

    // Instantiate the view and call the display method
    $aidRequestView = new AidRequestView();
    $aidRequestView->displayAidRequestFormAndFiles($aidTypes);
}

    public function showAidRequests(){
        $aidRequests = $this->model->getAidRequests();
        require_once __DIR__ . '/../views/adminView/AidView.php';
        $view = new AidView();
        $view->displayAidRequests($aidRequests);
    }

    public function showAidRequestDetails($id) {
        // Ensure we're passing just the ID number
        $requestId = is_array($id) ? $id['id'] : intval($id);
        $aidRequest = $this->model->getAidRequestById($requestId);
        
        require_once __DIR__ . '/../views/adminView/AidDetailsView.php';
        $view = new AidDetailsView();
        $view->displayAidRequestDetails($aidRequest);
    }

    public function getAidRequestById($id) {
        // Ensure we're passing just the ID number
        $requestId = is_array($id) ? $id['id'] : intval($id);
        return $this->model->getAidRequestById($requestId);
    }

     public function acceptRequest($id) {
        $requestId = is_array($id) ? $id['id'] : intval($id);
        return $this->model->acceptAidRequest($requestId);
    }

    public function refuseRequest($id) {
        $requestId = is_array($id) ? $id['id'] : intval($id);
        return $this->model->refuseAidRequest($requestId);
    }



    public function handleAidRequest($postData, $files) {
        try {
            // Validate the form data
            if (!isset($postData['aid_type_id']) || empty($postData['aid_type_id'])) {
                throw new Exception('Type d\'aide non sélectionné');
            }

            if (!isset($_SESSION['user_id'])) {
                throw new Exception('Utilisateur non connecté');
            }

            // Check if a file was uploaded
            if (!isset($files['dossier']) || $files['dossier']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Veuillez fournir un dossier valide');
            }

            // Upload the file
            $uploadResult = $this->fileUploadHelper->saveFile($files['dossier']);
            if (!$uploadResult['success']) {
                throw new Exception('Erreur lors du téléchargement du fichier: ' . $uploadResult['error']);
            }

            // Create the aid request with the file path
            $aidRequest = $this->model->createAidRequest(
                $_SESSION['user_id'],
                $postData['aid_type_id'],
                $uploadResult['filePath']
            );

            if (!$aidRequest) {
                throw new Exception('Erreur lors de la création de la demande d\'aide');
            }

            $_SESSION['success'] = 'Demande d\'aide soumise avec succès';
            header('Location: ' . BASE_URL . '/aide');
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/aide');
            exit;
        }
    }
    public function getAidTypes() {
        return $this->model->getAllAidTypes();
    }

     public function getAllFileTypes() {
        return $this->model->getAllFileTypes(); 
    }

    
}

?>