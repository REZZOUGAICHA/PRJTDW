<?php
require_once __DIR__ . '/../models/partnerModel.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';



class partnerController {
    private $partnerModel;
    private $fileUploadHelper;

    public function __construct() {
        $this->partnerModel = new partnerModel();
        $this->fileUploadHelper = new FileUploadHelper('uploads/partners/');
        $this->fileUploadHelper->setAllowedTypes(['image/jpeg', 'image/png', 'image/gif']);
        $this->fileUploadHelper->setMaxFileSize(5242880); // 5MB
    }

    public function getCategories() {
        return $this->partnerModel->getCategories(); // You'll need to add this to your model
    }

    public function getCategoryById($category_id) {
        return $this->partnerModel->getCategoryById($category_id); // You'll need to add this to your model
    }
    //---------------------- PARTNER ----------------------
    public function getPartnersByCategory() {
        return $this->partnerModel->getPartnersByCategory();
    }
    

    // ---------------------- CRUDS ----------------------
    public function createPartner($user_id, $name, $city, $description, $logo_url, $category_id, $link) {
        $this->partnerModel->createPartner($user_id, $name, $city, $description, $logo_url, $category_id, $link);
    }

    // -------------------------------------------------------------------------------------------
    public function updatePartner($id, $name, $city, $description, $logo_url, $category_id, $link) {
        $this->partnerModel->updatePartner($id, $name, $city, $description, $logo_url, $category_id, $link);
    }

    // -------------------------------------------------------------------------------------------
    public function deletePartner($id) {
        $this->partnerModel->deletePartner($id);
    }

    // -------------------------------------------------------------------------------------------
    function showPartnerForAdmin() {  //of the admin
        require_once __DIR__ . '/../Views/adminView/partnerView.php';
        $view = new partnerView();
        $view->displayPartners();
    }

    function showPartnerForUser() {  //of the user
        require_once __DIR__ . '/../Views/userView/partnerView.php';
        $view = new partnerView();
        $view->displaypartner();
    }
    function getPartnerById($id) {  
    return $this->partnerModel->getPartnerById($id);
}

   
    

public function showPartnerDetail($id) {
    require_once __DIR__ . '/../Views/adminView/partnerDetailView.php';
    $view = new PartnerDetailView();
    $view->displayPartnerDetail($id);
}

    // -------------------------------------------------------------------------------------------
 public function handlePartnerUpdate($postData, $files) {
    
        try {
            if (empty($postData['partner_id']) || empty($postData['name']) || empty($postData['city'])) {
                throw new Exception('Required fields are missing');
            }

            $partner = $this->partnerModel->getPartnerById($postData['partner_id']);
            if (!$partner) {
                throw new Exception('Partner not found');
            }

            $logo_url = $partner['logo_url'];
            if (!empty($files['logo']['tmp_name'])) {
                $result = $this->fileUploadHelper->saveFile($files['logo']);
                if (!$result['success']) {
                    throw new Exception($result['error']);
                }
                $logo_url = $result['filePath'];
            }

            $this->partnerModel->updatePartner(
                $postData['partner_id'],
                $postData['name'],
                $postData['city'],
                $postData['description'] ?? '',
                $logo_url,
                $postData['category_id'] ?? null,
                $partner['link']
            );

            $_SESSION['success'] = 'Partner updated successfully';
            header('Location: ' . BASE_URL . '/admin/partner?id=' . $postData['partner_id']);
            
        } catch (Exception $e) {
            error_log('Error updating partner: ' . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/partner?id=' . $postData['partner_id'] . '&edit=true');
        }
        exit;
    }


    

}
?>
