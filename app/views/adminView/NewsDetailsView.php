<?php
require_once __DIR__ . '/../../controllers/NewsController.php';

class NewsDetailView {
    private $newsController;

    public function __construct() {
        $this->newsController = new NewsController();
    }

   public function displayNewsDetail($newsId) {
    if (!isset($newsId)) {
        header('Location: ' . BASE_URL . '/admin/news');
        exit;
    }

    // Fetch news from the database based on the ID
    $news = $this->newsController->getNewsById($newsId);
    $isEditing = isset($_GET['edit']) && $_GET['edit'] === 'true';
    ?>
    <div class="min-h-screen bg-gray-100 py-8">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">Détails de l'actualité</h1>
                    <?php if (!$isEditing): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/news?id=<?php echo $newsId; ?>&edit=true" 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                            Modifier
                        </a>
                    <?php endif; ?>
                </div>

                <div class="p-6">
                    <!-- News Details -->
                    <?php if ($isEditing): ?>
                        <form action="<?php echo BASE_URL; ?>/admin/news?action=update" method="POST" enctype="multipart/form-data" class="space-y-6">
                            <input type="hidden" name="news_id" value="<?php echo htmlspecialchars($news['id']); ?>">

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Titre</label>
                                    <input type="text" name="name"  
                                        value="<?php echo htmlspecialchars($news['name']); ?>"
                                        class="w-full rounded-lg border-gray-300 text-sm p-2 focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium mb-1">Description</label>
                                    <textarea name="description" rows="6"
                                        class="w-full rounded-lg border-gray-300 text-sm p-2 focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($news['description']); ?></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Image</label>
                                    <input type="file" name="picture" accept="image/*"
                                        class="w-full text-sm text-gray-500 border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="flex justify-between pt-6 border-t border-gray-100">
                                <button type="button" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/news?action=delete&id=<?php echo $newsId; ?>'"
                                        class="px-4 py-2 text-sm text-red-600 bg-red-50 rounded hover:bg-red-100">
                                        Supprimer
                                </button>
                                <div class="space-x-3">
                                    <a href="<?php echo BASE_URL; ?>/admin/news?id=<?php echo $newsId; ?>" 
                                        class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50">
                                        Annuler
                                    </a>
                                    <button type="submit" 
                                            class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                            Sauvegarder
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($news['name']); ?></h2>
                                <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($news['description'])); ?></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Image</label>
                                <img src="<?php echo htmlspecialchars($news['picture_url']); ?>" 
                                    alt="Image <?php echo htmlspecialchars($news['name']); ?>"
                                    class="w-1/2 h-auto mt-2 border border-gray-200 rounded-lg mx-auto shadow-md">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

}
?>