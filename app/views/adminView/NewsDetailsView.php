<?php
require_once __DIR__ . '/../../controllers/NewsController.php';

class NewsDetailsView {
    private $newsController;

    public function __construct() {
        $this->newsController = new NewsController();
    }

    public function displayAnnounceDetail($id) {
        $announce = $this->newsController->getAnnounceById($id);
        
        if (!$announce) {
            echo '<div class="text-red-600">Actualité non trouvée</div>';
            return;
        }

        ?>
        <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold"><?php echo htmlspecialchars($announce['name']); ?></h2>
                <div class="space-x-2">
                    <a href="<?php echo BASE_URL; ?>/admin/news/<?php echo $announce['id']; ?>/edit" 
                       class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                    <button onclick="confirmDelete(<?php echo $announce['id']; ?>)"
                            class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer
                    </button>
                </div>
            </div>

            <div class="space-y-6">
                <?php if (!empty($announce['picture_url'])): ?>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Image</h3>
                    <img src="<?php echo BASE_URL . '/uploads/announces/' . htmlspecialchars($announce['picture_url']); ?>" 
                         alt="<?php echo htmlspecialchars($announce['name']); ?>"
                         class="max-w-full h-auto rounded-lg shadow">
                </div>
                <?php endif; ?>

                <div>
                    <h3 class="text-lg font-semibold">Description</h3>
                    <p class="mt-2 text-gray-600 whitespace-pre-line">
                        <?php echo nl2br(htmlspecialchars($announce['description'])); ?>
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold">Date de création</h3>
                    <p class="mt-2 text-gray-600">
                        <?php echo date('d/m/Y H:i', strtotime($announce['created_at'])); ?>
                    </p>
                </div>
            </div>
        </div>

        <script>
        function confirmDelete(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')) {
                window.location.href = `${BASE_URL}/admin/news/${id}/delete`;
            }
        }
        </script>
        <?php
    }
}
?>