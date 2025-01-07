<?php
require_once __DIR__ . '/../../controllers/partnerController.php';
require_once __DIR__ . '/../../views/userView/TableView.php';


class partnerView {
    private $partnerController;

    public function __construct() {
        $this->partnerController = new partnerController();
    }

    public function displayPartners() {
        $categorizedPartners = $this->partnerController->getPartnersByCategory();
        
        // Transform data
        $flattenedPartners = [];
        foreach ($categorizedPartners as $categoryId => $categoryData) {
            foreach ($categoryData['partners'] as $partner) {
                // Format discounts
                $discountStr = '';
                if (!empty($partner['discounts'])) {
                    $discountItems = array_map(function($discount) {
                        return "{$discount['name']}: {$discount['percentage']}%";
                    }, $partner['discounts']);
                    $discountStr = implode('<br>', $discountItems);
                }

                $flattenedPartners[] = [
                    'id' => $partner['id'], 
                    'name' => $partner['name'],
                    'city' => $partner['city'],
                    'category' => $categoryData['name'],
                    'discounts' => $discountStr
                ];
            }
        }

        // Define columns
        $columns = [
            ['label' => 'Nom', 'field' => 'name'],
            ['label' => 'Ville', 'field' => 'city'],
            ['label' => 'Catégorie', 'field' => 'category'],
            ['label' => 'Réductions', 'field' => 'discounts']
        ];

        // Define actions
        $actions = [
            function($row) {
                return sprintf(
                    '<a href="%s/admin/partner?id=%s" class="text-blue-600 hover:text-blue-800 hover:underline">Voir plus</a>',
                    BASE_URL,
                    htmlspecialchars($row['id'])
                );
            }
        ];

        echo '<div class="partner-table-container">';
        $tableView = new TableView();
        $tableView->displayTable($flattenedPartners, $columns, $actions);
        echo '</div>';

        // Your existing JavaScript for filters
        ?>
        <script>
            $(document).ready(function() {
                initializeTableFilters('.partner-table-container', [
                    { label: 'Ville', columnIndex: 2 },
                    { label: 'Catégorie', columnIndex: 3 }
                ]);
            });
        </script>
        <?php
    }
}
?>