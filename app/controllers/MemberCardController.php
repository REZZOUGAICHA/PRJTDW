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
            // Check if QR code is already stored in database
            if (empty($cardDetails['QR_LINK'])) {
                // Generate new QR code only if it's not already stored
                $qrLink = QRCodeHelper::generateQRCode($cardDetails['card_number']);
                // Save the QR link to the user table
                $this->memberCardModel->updateQRLink($memberId, $qrLink);
                // Update cardDetails with new QR link
                $cardDetails['QR_LINK'] = $qrLink;
            }

            // Include the card view
            require_once __DIR__ . '/../views/userView/MemberCardView.php';
        } else {
            echo "Member not found or not a valid member.";
        }
    }
}
?>
