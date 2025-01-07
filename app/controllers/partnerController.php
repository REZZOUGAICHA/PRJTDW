<?php
require_once __DIR__ . '/../models/partnerModel.php';



class partnerController {
    private $partnerModel;

    public function __construct() {
        $this->partnerModel = new partnerModel();
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

}
?>
