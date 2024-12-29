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
            <input type="text" 
            id="searchInput" 
            placeholder="Rechercher..." 
            class=" max-w-md mx-auto block mb-8 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
>
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
