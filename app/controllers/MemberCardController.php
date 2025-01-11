<?php
require_once __DIR__ . '/../models/MemberCardModel.php';
require_once __DIR__ . '/../helpers/QRCodeHelper.php';

class MemberCardController {
    private $memberCardModel;

    public function __construct() {
        $this->memberCardModel = new MemberCardModel();
    }

    public function displayMemberCard($memberId) {
    $cardDetails = $this->memberCardModel->getMemberCard($memberId);
    
    if ($cardDetails) {
        // Generate QR code for the card
        if (!file_exists(__DIR__ . "/../../public/qr_codes/{$cardDetails['card_number']}.png")) {
            $qrCode = QRCodeHelper::generateQRCode($cardDetails['card_number']);
        }

        // Include the card view (not the profile view)
        require_once __DIR__ . '/../views/userView/MemberCardView.php';
    } else {
        echo "Member not found or not a valid member.";
    }
}
}
?>
