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
        if (empty($cardDetails['QR_LINK'])) {
            // Create a string or JSON with all necessary data
            $qrData = json_encode([
                'card_number' => $cardDetails['card_number'],
                'member_name' => $cardDetails['first_name'] . ' ' . $cardDetails['last_name'],
                'issue_date' => $cardDetails['issue_date'],
                'expiration_date' => $cardDetails['expiration_date'],
                'card_type' => $cardDetails['card_type'],
                'association' => $cardDetails['asso_name']
            ]);
            
            // Generate QR code with all data
            $qrLink = QRCodeHelper::generateQRCode($qrData);
            $this->memberCardModel->updateQRLink($memberId, $qrLink);
            $cardDetails['QR_LINK'] = $qrLink;
        }
        
        require_once __DIR__ . '/../views/userView/MemberCardView.php';
    } else {
        echo "Member not found or not a valid member.";
    }
}
}
?>
