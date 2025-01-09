<?php
require_once __DIR__ . '/../../controllers/PartnerController.php';
// here im doing the cruds for partners and each partner with his offers and discounts 
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
            <div class="max-w-3xl mx-auto px-4">
                <div class="bg-white shadow rounded-lg border border-gray-100">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h1 class="text-xl font-semibold">Détails du Partenaire</h1>
                        <?php if (!$isEditing): ?>
                            <a href="<?php echo BASE_URL; ?>/admin/partner?id=<?php echo $partnerId; ?>&edit=true" 
                                class="flex items-center px-3 py-2 text-sm bg-gray-50 rounded hover:bg-gray-100 border border-gray-200">
                                    Modifier
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="p-6">
                        <!-- Partner Header -->
                        <div class="flex items-start gap-6 mb-6 pb-6 border-b border-gray-100">
                            <div class="w-24 h-24 rounded-lg border border-gray-200 bg-white flex items-center justify-center">
                                <img src="<?php echo htmlspecialchars($partner['logo_url']); ?>" 
                                    alt="Logo <?php echo htmlspecialchars($partner['name']); ?>"
                                    class="w-full h-full object-contain p-2"
                                    onerror="this.src='<?php echo BASE_URL; ?>/public/images/default-logo.png'">
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-semibold mb-2">
                                    <?php echo htmlspecialchars($partner['name']); ?>
                                </h2>
                                <div class="flex items-center text-gray-500 text-sm">
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
                                            <label class="block text-sm font-medium mb-1">Nom</label>
                                            <input type="text" name="name" 
                                                value="<?php echo htmlspecialchars($partner['name']); ?>"
                                                class="w-full rounded border-gray-200 text-sm">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Ville</label>
                                            <input type="text" name="city" 
                                                value="<?php echo htmlspecialchars($partner['city']); ?>"
                                                class="w-full rounded border-gray-200 text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium mb-1">Catégorie</label>
                                            <select name="category_id" class="w-full rounded border-gray-200 text-sm">
                                                <?php foreach ($this->getCategories() as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>" 
                                                            <?php echo $category['id'] == $partner['category_id'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium mb-1">Logo</label>
                                            <input type="file" name="logo" accept="image/*"
                                                class="w-full text-sm text-gray-500">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-1">Description</label>
                                        <textarea name="description" rows="8"
                                                class="w-full rounded border-gray-200 text-sm"><?php echo htmlspecialchars($partner['description']); ?></textarea>
                                    </div>
                                </div>

                                <div class="flex justify-between pt-6 border-t border-gray-100">
                                    <button type="button" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/partner?action=delete&id=<?php echo $partnerId; ?>'"
                                            class="px-4 py-2 text-sm text-red-600 bg-red-50 rounded hover:bg-red-100">
                                            Supprimer
                                    </button>
                                    <div class="space-x-3">
                                        <a href="<?php echo BASE_URL; ?>/admin/partner?id=<?php echo $partnerId; ?>" 
                                            class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50">
                                            Annuler
                                        </a>
                                        <button type="submit" 
                                                class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                                Sauvegarder
                                        </button>
                                    </div>
                                </div>
                                </form> <!-- Close the partner update form here -->

<?php if ($isEditing): ?>
    <!-- Discounts Section -->
    <div class="mt-8 pt-6 border-t border-gray-100">
        <h3 class="text-lg font-medium mb-4">Discounts</h3>
        
        <?php
        // Debug output to check the data structure
        // echo '<pre>'; print_r($partnerData); echo '</pre>';
        
        $partnerData = $this->partnerController->getPartnersByCategory();
        $partnerDiscounts = [];
        foreach ($partnerData as $category) {
            foreach ($category['partners'] as $p) {
                if ($p['id'] == $partnerId) {
                    $partnerDiscounts = $p['discounts'] ?? [];
                    break 2;
                }
            }
        }
        
        $cardTypes = $this->partnerController->getCardTypes();
        ?>
        
        <!-- Existing Discounts -->
        <div class="space-y-4">
            <?php foreach ($partnerDiscounts as $discount): ?>
                <form action="<?php echo BASE_URL; ?>/admin/partner?action=discount/update" method="POST" class="space-y-4 p-4 bg-gray-50 rounded-lg">
                    <input type="hidden" name="partner_id" value="<?php echo $partnerId; ?>">
                    <input type="hidden" name="discount_id" value="<?php echo htmlspecialchars($discount['id'] ?? ''); ?>">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Name and Percentage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" 
                                   name="name"
                                   value="<?php echo htmlspecialchars($discount['name'] ?? ''); ?>"
                                   class="w-full rounded border-gray-200 text-sm"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Percentage</label>
                            <input type="number" 
                                   name="percentage"
                                   value="<?php echo htmlspecialchars($discount['percentage'] ?? ''); ?>"
                                   class="w-full rounded border-gray-200 text-sm"
                                   min="0"
                                   max="100"
                                   required>
                        </div>
                        
                        <!-- Card Type and Discount Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Card Type</label>
                            <select name="card_type_name" class="w-full rounded border-gray-200 text-sm" required>
                                <?php foreach ($cardTypes as $cardType): ?>
                                    <option value="<?php echo htmlspecialchars($cardType['name']); ?>"
                                            <?php echo ($discount['card_type_id'] == $cardType['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cardType['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type</label>
                            <select name="discount_type" class="w-full rounded border-gray-200 text-sm" required>
                                <option value="regular" <?php echo ($discount['discount_type'] == 'regular') ? 'selected' : ''; ?>>Regular</option>
                                <option value="special" <?php echo ($discount['discount_type'] == 'special') ? 'selected' : ''; ?>>Special</option>
                            </select>
                        </div>
                        
                        <!-- Start Date and End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" 
                                   name="start_date"
                                   value="<?php echo date('Y-m-d', strtotime($discount['start_date'])); ?>"
                                   class="w-full rounded border-gray-200 text-sm"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" 
                                   name="end_date"
                                   value="<?php echo date('Y-m-d', strtotime($discount['end_date'])); ?>"
                                   class="w-full rounded border-gray-200 text-sm"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" 
                                  class="w-full rounded border-gray-200 text-sm" 
                                  rows="3"
                                  required><?php echo htmlspecialchars($discount['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        <button type="submit" class="px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded hover:bg-blue-100">
                            Update
                        </button>
                        
                        <a href="<?php echo BASE_URL; ?>/admin/partner?action=discount/delete&partner_id=<?php echo $partnerId; ?>&discount_id=<?php echo $discount['id']; ?>"
                           onclick="return confirm('Are you sure you want to delete this discount?')"
                           class="px-4 py-2 text-sm text-red-600 bg-red-50 rounded hover:bg-red-100">
                            Delete
                        </a>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
        
        <!-- Add New Discount -->
        <div class="mt-4">
            <button type="button" 
                    onclick="document.getElementById('newDiscountForm').classList.toggle('hidden')"
                    class="text-sm text-blue-600 hover:text-blue-700">
                + Add New Discount
            </button>
            
            <div id="newDiscountForm" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                <form action="<?php echo BASE_URL; ?>/admin/partner?action=discount/add" method="POST" class="space-y-4">
                    <input type="hidden" name="partner_id" value="<?php echo $partnerId; ?>">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Name and Percentage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" class="w-full rounded border-gray-200 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Percentage</label>
                            <input type="number" name="percentage" class="w-full rounded border-gray-200 text-sm" min="0" max="100" required>
                        </div>
                        
                        <!-- Card Type and Discount Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Card Type</label>
                            <select name="card_type_name" class="w-full rounded border-gray-200 text-sm" required>
                                <?php foreach ($cardTypes as $cardType): ?>
                                    <option value="<?php echo htmlspecialchars($cardType['name']); ?>">
                                        <?php echo htmlspecialchars($cardType['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type</label>
                            <select name="discount_type" class="w-full rounded border-gray-200 text-sm" required>
                                <option value="regular">Regular</option>
                                <option value="special">Special</option>
                            </select>
                        </div>
                        
                        <!-- Start Date and End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" class="w-full rounded border-gray-200 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" class="w-full rounded border-gray-200 text-sm" required>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" 
                                  class="w-full rounded border-gray-200 text-sm" 
                                  rows="3"
                                  required></textarea>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                            Add Discount
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php


$cardTypes = $this->partnerController->getCardTypes();
?>

<!-- Offer Form -->
<div class="space-y-4">
    <?php foreach ($partnerDiscounts as $offer): ?>
        <form action="<?php echo BASE_URL; ?>/admin/partner?action=offer/update" method="POST" class="space-y-4 p-4 bg-gray-50 rounded-lg">
            <input type="hidden" name="partner_id" value="<?php echo $partnerId; ?>">
            <input type="hidden" name="offer_id" value="<?php echo htmlspecialchars($offer['id'] ?? ''); ?>">
            
            <div class="grid grid-cols-2 gap-4">
                <!-- Offer Name and Dates -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Offer Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($offer['name'] ?? ''); ?>" class="w-full rounded border-gray-200 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="<?php echo date('Y-m-d', strtotime($offer['start_date'])); ?>" class="w-full rounded border-gray-200 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="<?php echo date('Y-m-d', strtotime($offer['end_date'])); ?>" class="w-full rounded border-gray-200 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Type</label>
                    <select name="card_type_name" class="w-full rounded border-gray-200 text-sm" required>
                        <?php foreach ($cardTypes as $cardType): ?>
                            <option value="<?php echo htmlspecialchars($cardType['name']); ?>" <?php echo ($offer['card_type_id'] == $cardType['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cardType['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Offer Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" class="w-full rounded border-gray-200 text-sm" rows="3" required><?php echo htmlspecialchars($offer['description'] ?? ''); ?></textarea>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <button type="submit" class="px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded hover:bg-blue-100">Update</button>
                <a href="<?php echo BASE_URL; ?>/admin/partner?action=offer/delete&partner_id=<?php echo $partnerId; ?>&offer_id=<?php echo $offer['id']; ?>" onclick="return confirm('Are you sure you want to delete this offer?')" class="px-4 py-2 text-sm text-red-600 bg-red-50 rounded hover:bg-red-100">Delete</a>
            </div>
        </form>
    <?php endforeach; ?>
</div>

<!-- Add Offer Form -->
<form action="<?php echo BASE_URL; ?>/admin/partner?action=offer/add" method="POST" class="space-y-4 p-4 bg-gray-50 rounded-lg">
    <input type="hidden" name="partner_id" value="<?php echo $partnerId; ?>">
    
    <div class="grid grid-cols-2 gap-4">
        <!-- Offer Name and Dates -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Offer Name</label>
            <input type="text" name="name" class="w-full rounded border-gray-200 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
            <input type="date" name="start_date" class="w-full rounded border-gray-200 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
            <input type="date" name="end_date" class="w-full rounded border-gray-200 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Card Type</label>
            <select name="card_type_name" class="w-full rounded border-gray-200 text-sm" required>
                <?php foreach ($cardTypes as $cardType): ?>
                    <option value="<?php echo htmlspecialchars($cardType['name']); ?>"><?php echo htmlspecialchars($cardType['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <!-- Offer Description -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" class="w-full rounded border-gray-200 text-sm" rows="3" required></textarea>
    </div>
    
    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <button type="submit" class="px-4 py-2 text-sm text-green-600 bg-green-50 rounded hover:bg-green-100">Add Offer</button>
    </div>
</form>

                                    
    
                                <div class="pt-6 border-t border-gray-100">
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/partner?action=delete&id=<?php echo $partnerId; ?>'"
                                                class="px-4 py-2 text-sm text-red-600 bg-red-50 rounded hover:bg-red-100">
                                                Supprimer
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>/partenaires" 
                                            class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-50">
                                            ← Retour
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

    
// Add this method to your partnerView class

public function displayCreatePartnerForm() {
    $categories = $this->partnerController->getCategories();
    ?>
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Ajouter un nouveau partenaire</h2>
        
        <form action="<?php echo BASE_URL; ?>/admin/partner/create" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF jusqu'à 5MB</p>
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

    private function getCategories() {
        return $this->partnerController->getCategories();
    }
}
?>