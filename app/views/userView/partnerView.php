<?php
require_once  'CardView.php';
require_once __DIR__ . '/../../controllers/partnerController.php';

class PartnerView {
    public function displaypartner() {
        $partnerController = new partnerController();
        $categories = $partnerController->getPartnersByCategory();

        $cardView = new CardView();

        ?>
        <div class="w-full">
            <?php foreach ($categories as $category): ?>
                <div class="mb-12">
                    <?php 
                    $cardView->displaySection(
                        $category['partners'], 
                        htmlspecialchars($category['name']),
                        [
                            'title' => 'name', 
                            'description' => 'city', 
                            'image' => 'logo_url', 
                            'link' => 'link',
                            'discounts' => 'discounts' 
                        ]
                    ); 
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
?>
