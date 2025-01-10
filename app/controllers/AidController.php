<?php

require_once __DIR__ . '/../models/AidModel.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';


class AidController {
    private $model;
    private $fileUploadHelper;

    public function __construct() {
        $this->model = new AidModel();
        $this->fileUploadHelper = new FileUploadHelper();
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


    public function handleAidRequest($postData, $files) {
        try {
            // Validate the form data
            if (!isset($postData['aid_type_id']) || empty($postData['aid_type_id'])) {
                return ['error' => 'Type d\'aide non sélectionné'];
            }

            if (!isset($_SESSION['user_id'])) {
                return ['error' => 'Utilisateur non connecté'];
            }

            // Process uploaded files
            $fileData = [];
            if (!empty($files)) {
                foreach ($files['files'] as $fileTypeId => $fileInfo) {
                    if ($fileInfo['error'] === UPLOAD_ERR_OK) {
                        $uploadResult = $this->fileUploadHelper->saveFile($fileInfo);
                        if ($uploadResult['success']) {
                            $fileData[] = [
                                'file_type_id' => $fileTypeId,
                                'file_path' => $uploadResult['filePath']
                            ];
                        } else {
                            return ['error' => 'Erreur lors du téléchargement du fichier: ' . $uploadResult['error']];
                        }
                    }
                }
            }

            // Save the aid request
            $requestSaved = $this->model->createAidRequest(
                $_SESSION['user_id'],
                $postData['aid_type_id'],
                $fileData
            );

            if ($requestSaved) {
                return ['success' => true];
            } else {
                return ['error' => 'Erreur lors de la sauvegarde de la demande'];
            }
        } catch (Exception $e) {
            return ['error' => 'Une erreur est survenue: ' . $e->getMessage()];
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