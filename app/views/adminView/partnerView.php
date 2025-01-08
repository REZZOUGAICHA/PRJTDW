<?php
require_once __DIR__ . '/../../controllers/partnerController.php';
require_once __DIR__ . '/../../views/userView/TableView.php';


class partnerView {
    private $partnerController;

    public function __construct() {
        $this->partnerController = new partnerController();
    }

    public function displayPartners() {

        echo '<div class="mb-6">';
        echo '<a href="' . BASE_URL . '/admin/partenaires?action=create" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Ajouter un partenaire
            </a>';
        echo '</div>';
        $categorizedPartners = $this->partnerController->getPartnersByCategory();
        
        // Transform data
        $flattenedPartners = [];
        foreach ($categorizedPartners as $categoryId => $categoryData) {
            foreach ($categoryData['partners'] as $partner) {
                //  discounts
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

        //  columns
        $columns = [
            ['label' => 'Nom', 'field' => 'name'],
            ['label' => 'Ville', 'field' => 'city'],
            ['label' => 'Catégorie', 'field' => 'category'],
            ['label' => 'Réductions', 'field' => 'discounts']
        ];

        // actions
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

        //  for filters
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

    


public function displayCreatePartnerForm() {
    $categories = $this->partnerController->getCategories();
    ?>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Ajouter un nouveau partenaire</h2>
        
        <form action="<?php echo BASE_URL; ?>/admin/partenaires?action=create" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nom *</label>
                <input type="text" id="name" name="name" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- City -->
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700">Ville *</label>
                <input type="text" id="city" name="city" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie *</label>
                <select id="category_id" name="category_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Logo Upload -->
            <div>
                <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                <input type="file" id="logo" name="logo" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-sm text-gray-500">PNG, JPG jusqu'à 5MB</p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Ajouter le partenaire
                </button>
            </div>
        </form>
    </div>
    <?php
}
}
?>