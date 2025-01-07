<?php
require_once __DIR__ . '/../../controllers/PartnerController.php';

class PartnerDetailView {
    private $partnerController;
    
    public function __construct() {
        $this->partnerController = new PartnerController();
    }
    
    public function displayPartnerDetail($partnerId) {
        if (!isset($partnerId)) {
            header('Location: ' . BASE_URL . '/partenaires');
            exit;
        }

        $partner = $this->partnerController->getPartnerById($partnerId);
        $isEditing = isset($_GET['edit']) && $_GET['edit'] === 'true';
        ?>
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-100">
                    <!-- Simplified Header -->
                    <div class="px-6 py-4 bg-white border-b border-gray-100 flex justify-between items-center">
                        <h1 class="text-xl font-semibold text-gray-900">Détails du Partenaire</h1>
                        <?php if (!$isEditing): ?>
                            <a href="<?php echo BASE_URL; ?>/admin/partner?id=<?php echo $partnerId; ?>&edit=true" 
                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors border border-gray-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Modifier
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="p-6">
                        <!-- Partner Header Section -->
                        <div class="flex items-start space-x-6 mb-6 pb-6 border-b border-gray-100">
                            <div class="flex-shrink-0">
                                <div class="w-24 h-24 rounded-lg border border-gray-200 overflow-hidden bg-white flex items-center justify-center">
                                    <img src="<?php echo htmlspecialchars($partner['logo_url']); ?>" 
                                         alt="Logo <?php echo htmlspecialchars($partner['name']); ?>"
                                         class="w-full h-full object-contain p-2"
                                         onerror="this.src='<?php echo BASE_URL; ?>/assets/images/default-logo.png'">
                                </div>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                                    <?php echo htmlspecialchars($partner['name']); ?>
                                </h2>
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <?php echo htmlspecialchars($partner['city']); ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($isEditing): ?>
                            <form action="<?php echo BASE_URL; ?>/admin/partner?action=update" method="POST" enctype="multipart/form-data" class="space-y-6">
                                <input type="hidden" name="partner_id" value="<?php echo htmlspecialchars($partner['id']); ?>">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                            <input type="text" name="name" 
                                                   value="<?php echo htmlspecialchars($partner['name']); ?>"
                                                   class="block w-full rounded-md border-gray-200 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                            <input type="text" name="city" 
                                                   value="<?php echo htmlspecialchars($partner['city']); ?>"
                                                   class="block w-full rounded-md border-gray-200 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                                            <select name="category_id" 
                                                    class="block w-full rounded-md border-gray-200 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                                                <?php foreach ($this->getCategories() as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>" 
                                                            <?php echo $category['id'] == $partner['category_id'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                                            <input type="file" name="logo" accept="image/*"
                                                   class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 
                                                          file:rounded-md file:border file:border-gray-200 file:text-sm file:font-medium 
                                                          file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea name="description" rows="8"
                                                  class="block w-full rounded-md border-gray-200 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm"><?php echo htmlspecialchars($partner['description']); ?></textarea>
                                    </div>
                                </div>

                                <div class="flex justify-between pt-6 border-t border-gray-100">
                                    <button type="button" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/partner?action=delete&id=<?php echo $partnerId; ?>'"
                                            class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                        Supprimer
                                    </button>
                                    <div class="space-x-3">
                                        <a href="<?php echo BASE_URL; ?>/admin/partner?id=<?php echo $partnerId; ?>" 
                                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                            Annuler
                                        </a>
                                        <button type="submit" 
                                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                                            Sauvegarder
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">Catégorie</h3>
                                        <p class="text-sm text-gray-900">
                                            <?php 
                                            $category = $this->partnerController->getCategoryById($partner['category_id']);
                                            echo htmlspecialchars($category['name'] ?? 'Non catégorisé'); 
                                            ?>
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">Description</h3>
                                        <p class="text-sm text-gray-900">
                                            <?php echo nl2br(htmlspecialchars($partner['description'])); ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-gray-100">
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/partner?action=delete&id=<?php echo $partnerId; ?>'"
                                                class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                            Supprimer
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>/partenaires" 
                                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                            Retour
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function getCategories() {
        return $this->partnerController->getCategories();
    }
}
?>