<?php
require_once __DIR__ . '/../models/partnerModel.php';



class partnerController {
    private $partnerModel;

    public function __construct() {
        $this->partnerModel = new partnerModel();
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




    // -------------------------------------------------------------------------------------------

}
?>
