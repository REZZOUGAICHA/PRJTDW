<?php
require_once 'CardView.php';
require_once __DIR__ . '/../../controllers/partnerController.php';


class PartnerView {
    public function displaypartner() {
        $partnerController = new partnerController();
        $categories = $partnerController->getPartnersByCategory();
        $cardView = new CardView();

        // fetch unique cities 
        $cities = [];
        foreach ($categories as $category) {
            foreach ($category['partners'] as $partner) {
                if (!empty($partner['city'])) {
                    $cities[$partner['city']] = $partner['city'];
                }
            }
        }
        sort($cities);
        ?>

        <div class="w-full">
    <!-- container for search and filter-->
    <div class="flex gap-4 mb-8">
        
        <!-- search -->
        <input type="text" 
            id="searchInput" 
            placeholder="Rechercher..." 
            class=" max-w-md px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200"
        >

        <!-- filter -->
        <select id="cityFilter" 
            class=" max-w-xs px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200"
        >
            <option value="">Toutes les villes</option>
            <?php foreach ($cities as $city): ?>
                <option value="<?php echo htmlspecialchars($city); ?>">
                    <?php echo htmlspecialchars($city); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<!-- display partners selon category -->
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
    <script src="./Public/js/search.js"></script>
    <script src="./Public/js/filter.js"></script>
<?php
}
}
?>