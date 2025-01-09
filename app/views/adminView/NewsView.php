<?php
require_once __DIR__ . '/../../controllers/newsController.php';
require_once __DIR__ . '/../../views/userView/TableView.php';


class newsView {
    private $newsController;

    public function __construct() {
        $this->newsController = new newsController();
    }

    public function displaynews() {

        echo '<div class="mb-6">';
        echo '<a href="' . BASE_URL . '/admin/news?action=create" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Ajouter une actualité
            </a>';
        echo '</div>';
        
        $news = $this->newsController->getAllAnnounces()['announces'];

        
        // Transform data
        $flattenednews = [];
        foreach ($news as $newsItem) {
            $flattenednews[] = [
            
            'id' => $newsItem['id'],
            'name' => $newsItem['name'],
            'description' => $newsItem['description']
            ];
        }

        //  columns
        $columns = [
            ['label' => 'Nom', 'field' => 'name'],
            ['label' => 'Description', 'field' => 'description'],
            
        ];

        // actions
        $actions = [
            function($row) {
                return sprintf(
                    '<a href="%s/admin/news?id=%s" class="text-blue-600 hover:text-blue-800 hover:underline">Voir plus</a>',
                    BASE_URL,
                    htmlspecialchars($row['id'])
                );
            }
        ];

        echo '<div class="news-table-container">';
        $tableView = new TableView();
        $tableView->displayTable($flattenednews, $columns, $actions);
        echo '</div>';

        //  for filters
        ?>
        
        <?php
    }

    

    public function displayCreatenewsForm() {
    ?>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Ajouter une nouvelle actualité</h2>
        
        <form action="<?php echo BASE_URL; ?>/admin/news?action=create" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Titre *</label>
                <input type="text" id="title" name="title" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" id="image" name="image" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-sm text-gray-500">PNG, JPG jusqu'à 5MB</p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Ajouter l'actualité
                </button>
            </div>
        </form>
    </div>
    <?php
}
}
?>