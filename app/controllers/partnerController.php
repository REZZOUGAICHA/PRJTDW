<?php
require_once __DIR__ . '/../models/partnerModel.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';



class partnerController {
    private $partnerModel;
    private $fileUploadHelper;

    public function __construct() {
        $this->partnerModel = new partnerModel();
        //still not working when trying to update the logo 
        $this->fileUploadHelper = new FileUploadHelper('uploads/partners/');
        $this->fileUploadHelper->setAllowedTypes(['image/jpeg', 'image/png', 'image/gif']);
        $this->fileUploadHelper->setMaxFileSize(5242880); // 5MB
    }
    //---------------------- category ----------------------
    public function getCategories() {
        return $this->partnerModel->getCategories(); 
    }

    public function getpartners(){
        return $this->partnerModel->getpartners();
    }

    public function getCategoryById($category_id) {
        return $this->partnerModel->getCategoryById($category_id); 
    }
    //---------------------- PARTNER ----------------------
    public function getPartnersByCategory() {
        return $this->partnerModel->getPartnersByCategory();
    }

    // ---------------------- CRUDS ----------------------
    // Add this method to your partnerController class
public function handlePartnerCreate($postData, $files) {
    try {
        // Validate required fields
        if (empty($postData['name']) || empty($postData['city']) || empty($postData['category_id']) ||
            empty($postData['first_name']) || empty($postData['last_name']) || 
            empty($postData['email']) || empty($postData['password'])) {
            throw new Exception('Les champs obligatoires doivent être remplis');
        }

        // Handle logo upload if provided
        $logo_url = '';
        if (!empty($files['logo']['tmp_name'])) {
            $result = $this->fileUploadHelper->saveFile($files['logo']);
            if (!$result['success']) {
                throw new Exception($result['error']);
            }
            $logo_url = $result['filePath'];
        }

        // First create the user account for the partner
        $user_id = $this->partnerModel->createPartnerUser(
            $postData['first_name'],
            $postData['last_name'],
            $postData['email'],
            $postData['password'],
            $logo_url
        );

        // Then create the partner entry
        $partner_id = $this->partnerModel->createPartner(
            $user_id, // Use the newly created user's ID
            $postData['name'],
            $postData['city'],
            $postData['description'] ?? '',
            $logo_url,
            $postData['category_id']
        );

        $_SESSION['success'] = 'Partenaire ajouté avec succès';
        header('Location: ' . BASE_URL . '/admin/partner?id=' . $partner_id);
        return true;
        
    } catch (Exception $e) {
        error_log('Error creating partner: ' . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header('Location: ' . BASE_URL . '/admin/partenaires?action=create');
        return false;
    }
}
    // -------------------------------------------------------------------------------------------
    public function deletePartner($id) {
        $this->partnerModel->deletePartner($id);
    }
    // -------------------------------DISPLAY------------------------------------------------------------
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

    public function showPartnerDetail($id) {
    require_once __DIR__ . '/../Views/adminView/partnerDetailView.php';
    $view = new PartnerDetailView();
    $view->displayPartnerDetail($id);
}
    public function showCreatePartnerForm() {
        require_once __DIR__ . '/../Views/adminView/partnerView.php';
        $view = new partnerView();
        $view->displayCreatePartnerForm();
    }
// -------------------------------------------------------------------------------------------
    function getPartnerById($id) {  
    return $this->partnerModel->getPartnerById($id);
}
    // -------------------------------------------------------------------------------------------
public function handlePartnerUpdate($postData, $files) {
    
        try {
            //required or not 
            if (empty($postData['partner_id']) || empty($postData['name']) || empty($postData['city'])) {
                throw new Exception('Required fields are missing');
            }

            $partner = $this->partnerModel->getPartnerById($postData['partner_id']);
            if (!$partner) {
                throw new Exception('Partner not found');
            }
            //handle logo  -still not working :')-
            $logo_url = $partner['logo_url'];
            if (!empty($files['logo']['tmp_name'])) {
                $result = $this->fileUploadHelper->saveFile($files['logo']);
                if (!$result['success']) {
                    throw new Exception($result['error']);
                }
                $logo_url = $result['filePath'];
            }
             // Update data 
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
    public function handleDiscountUpdate($partnerId, $discountData) {
    try {
        $requiredFields = ['discount_id', 'card_type_name', 'name', 'description', 'percentage', 'discount_type', 'start_date', 'end_date'];
        foreach ($requiredFields as $field) {
            if (!isset($discountData[$field])) {
                throw new Exception("Missing required discount data: $field");
            }
        }

        $this->partnerModel->updatePartnerDiscount(
            $discountData['discount_id'],
            $partnerId,
            $discountData['card_type_name'],
            $discountData['name'],
            $discountData['description'],
            $discountData['percentage'],
            $discountData['discount_type'],
            $discountData['start_date'],
            $discountData['end_date']
        );

        $_SESSION['success'] = 'Discount updated successfully';
        return true;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        return false;
    }
}


public function handleDiscountAdd($partnerId, $discountData) {
    try {
        $requiredFields = ['card_type_name', 'name', 'description', 'percentage', 'discount_type', 'start_date', 'end_date'];
        foreach ($requiredFields as $field) {
            if (!isset($discountData[$field])) {
                throw new Exception("Missing required discount data: $field");
            }
        }

        $this->partnerModel->addPartnerDiscount(
            $partnerId,
            $discountData['card_type_name'],
            $discountData['name'],
            $discountData['description'],
            $discountData['percentage'],
            $discountData['discount_type'],
            $discountData['start_date'],
            $discountData['end_date']
        );

        $_SESSION['success'] = 'Discount added successfully';
        return true;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        return false;
    }
}

public function getCardTypes() {
    return $this->partnerModel->getCardTypes();
}

public function handleDiscountDelete($partnerId, $discountId) {
    try {
        $this->partnerModel->deletePartnerDiscount($discountId, $partnerId);
        $_SESSION['success'] = 'Discount deleted successfully';
        return true;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        return false;
    }
}

    public function showpartnersLogo() {
        require_once __DIR__ . '/../Views/userView/LandingView.php';
        $view = new landingView();
        $view->partnersLogoDisplay();
    }

    

}
?>
