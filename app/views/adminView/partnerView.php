<?php
require_once __DIR__ . '/../../controllers/partnerController.php';
require_once __DIR__ . '/../../views/userView/TableView.php';
require_once __DIR__ . '/../../helpers/LinkGenerator.php';
class partnerView {
    private $partnerController;

    public function __construct() {
        $this->partnerController = new partnerController();
    }

    public function displayPartners() {
        
        $categorizedPartners = $this->partnerController->getPartnersByCategory();
        
        // Transform 
        $flattenedPartners = [];
        foreach ($categorizedPartners as $categoryId => $categoryData) {
            foreach ($categoryData['partners'] as $partner) {
                // Format discounts as a string
                $discountStr = '';
                if (!empty($partner['discounts'])) {
                    $discountItems = array_map(function($discount) {
                        return "{$discount['name']}: {$discount['percentage']}%";
                    }, $partner['discounts']);
                    $discountStr = implode('<br>', $discountItems);
                }

                $flattenedPartners[] = [
                    'name' => $partner['name'],
                    'city' => $partner['city'],
                    'category' => $categoryData['name'],
                    'discounts' => $discountStr,
                    'link' => $partner['link']
                ];
            }
        }

        // Define columns for the table
        $columns = [
            ['label' => 'Nom', 'field' => 'name'],
            ['label' => 'Ville', 'field' => 'city'],
            ['label' => 'Catégorie', 'field' => 'category'],
            ['label' => 'Réductions', 'field' => 'discounts'],
            ['label' => 'Lien', 'field' => 'link']
        ];

        // Wrap table in a container div
        echo '<div class="partner-table-container">';
        
        // Display the table using TableView
        $tableView = new TableView();
        $tableView->displayTable($flattenedPartners, $columns);
        
        echo '</div>';
        
        // Initialize filters
        ?>
        <script>
            $(document).ready(function() {
                // Initialize filters for city and category columns
                initializeTableFilters('.partner-table-container', [
                    { label: 'Ville', columnIndex: 2 },      // Assuming city is the 2nd column
                    { label: 'Catégorie', columnIndex: 3 }   // Assuming category is the 3rd column
                ]);
            });
        </script>
        <?php
    }
}
?>