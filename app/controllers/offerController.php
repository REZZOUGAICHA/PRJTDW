<?php
require_once __DIR__ . '/../models/offerModel.php';
require_once __DIR__ . '/../models/partnerModel.php';

class offerController {
private $model;
private $partnerModel;

public function __construct() {
    $this->model = new offerModel();
    $this->partnerModel = new PartnerModel();
}

public function getOffersData() {
    return $this->model->getOffers();
}

public function getOffersByType() {
        return $this->model->getOffersByType();
    }

public function showOffer() {
    require_once __DIR__ . '/../Views/userView/offerView.php';
    $view = new offerView();
    $view->displayOffer();
}
// Handle offer update
public function handleOfferUpdate($partnerId, $offerData) {
    try {
        // Ensure all required fields are passed
        $requiredFields = ['offer_id', 'card_type_name', 'name', 'description', 'start_date', 'end_date'];
        foreach ($requiredFields as $field) {
            if (!isset($offerData[$field])) {
                throw new Exception("Missing required offer data: $field");
            }
        }

        // Call the update method
        $this->partnerModel->updatePartnerOffer(
            $offerData['offer_id'],
            $partnerId,
            $offerData['card_type_name'],
            $offerData['name'],
            $offerData['description'],
            $offerData['start_date'],
            $offerData['end_date']
        );

        $_SESSION['success'] = 'Offer updated successfully';
        return true;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        return false;
    }
}

// Handle offer add
public function handleOfferAdd($partnerId, $offerData) {
    try {
        // Ensure all required fields are passed
        $requiredFields = ['card_type_name', 'name', 'description', 'start_date', 'end_date'];
        foreach ($requiredFields as $field) {
            if (!isset($offerData[$field])) {
                throw new Exception("Missing required offer data: $field");
            }
        }

        // Call the add method
        $this->partnerModel->addPartnerOffer(
            $partnerId,
            $offerData['card_type_name'],
            $offerData['name'],
            $offerData['description'],
            $offerData['start_date'],
            $offerData['end_date']
        );

        $_SESSION['success'] = 'Offer added successfully';
        return true;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        return false;
    }
}

public function handleOfferDelete($partnerId, $offerId) {
    try {
        $this->partnerModel->deletePartnerOffer($offerId, $partnerId);
        $_SESSION['success'] = 'Offer deleted successfully';
        return true;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        return false;
    }
}

}
?>