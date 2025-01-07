<?php
// File: Views/adminView/partnerDetailView.php
class PartnerDetailView {
    private $partnerController;

    public function __construct() {
        $this->partnerController = new PartnerController();
    }

    public function displayPartnerDetail($partnerId) {
        $partner = $this->partnerController->getPartnerById($partnerId);
        if (!$partner) {
            echo '<div class="p-4 bg-red-100 text-red-700 rounded-lg">Partner not found.</div>';
            return;
        }

        $isEditing = isset($_GET['edit']) && $_GET['edit'] === 'true';
        ?>
        <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                        <h1 class="text-xl font-bold text-white">Partner Details</h1>
                    </div>

                    <div class="p-6">
                        <!-- Partner Header Section -->
                        <div class="flex items-start space-x-6 mb-6 pb-6 border-b border-gray-200">
                            <div class="flex-shrink-0">
                                <img src="<?php echo htmlspecialchars($partner['logo_url']); ?>" 
                                     alt="Partner logo"
                                     class="h-32 w-32 object-contain border rounded-lg"
                                     onerror="this.src='<?php echo BASE_URL; ?>/assets/images/default-logo.png'">
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900">
                                    <?php echo htmlspecialchars($partner['name']); ?>
                                </h2>
                                <p class="text-gray-500"><?php echo htmlspecialchars($partner['city']); ?></p>
                                <?php if (!$isEditing): ?>
                                    <div class="mt-4">
                                        <a href="<?php echo BASE_URL; ?>/admin/partner?action=detail&id=<?php echo $partnerId; ?>&edit=true" 
                                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            Edit Partner
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($isEditing): ?>
                            <!-- Edit Form -->
                            <form action="<?php echo BASE_URL; ?>/admin/partner?action=update" method="POST" enctype="multipart/form-data" class="space-y-6">
                                <input type="hidden" name="partner_id" value="<?php echo htmlspecialchars($partner['id']); ?>">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" name="name" 
                                               value="<?php echo htmlspecialchars($partner['name']); ?>"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" name="city" 
                                               value="<?php echo htmlspecialchars($partner['city']); ?>"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" rows="4"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($partner['description']); ?></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Logo</label>
                                        <input type="file" name="logo" accept="image/*"
                                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                                                      file:rounded-full file:border-0 file:text-sm file:font-semibold 
                                                      file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Category</label>
                                        <select name="category_id" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <?php foreach ($this->getCategories() as $category): ?>
                                                <option value="<?php echo $category['id']; ?>" 
                                                        <?php echo $category['id'] == $partner['category_id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex justify-between pt-6 border-t">
                                    <button type="button" onclick="deletePartner()"
                                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                        Delete Partner
                                    </button>
                                    <div class="space-x-3">
                                        <a href="<?php echo BASE_URL; ?>/admin/partners/detail/<?php echo $partnerId; ?>" 
                                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            Cancel
                                        </a>
                                        <button type="submit" 
                                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <!-- Display Partner Details -->
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Category</h3>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <?php 
                                            $category = $this->partnerController->getCategoryById($partner['category_id']);
                                            echo htmlspecialchars($category['name'] ?? 'Uncategorized'); 
                                            ?>
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Location</h3>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <?php echo htmlspecialchars($partner['city']); ?>
                                        </p>
                                    </div>

                                    <div class="col-span-2">
                                        <h3 class="text-sm font-medium text-gray-500">Description</h3>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <?php echo nl2br(htmlspecialchars($partner['description'])); ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t">
                                    <div class="flex justify-end space-x-4">
                                        <button type="button" onclick="deletePartner()"
                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                            Delete Partner
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>/admin/partners" 
                                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            Back to Partners
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
        async function deletePartner() {
            if (!confirm('Are you sure you want to delete this partner? This action cannot be undone.')) {
                return;
            }

            const partnerId = <?php echo json_encode($partner['id']); ?>;
            
            try {
                const response = await fetch(`<?php echo BASE_URL; ?>/admin/partner?action=delete&id=${partnerId}`, {
                    method: 'POST'
                });

                const result = await response.json();
                
                if (result.success) {
                    window.location.href = '<?php echo BASE_URL; ?>/admin/partners';
                } else {
                    alert(result.message || 'Error deleting partner');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting partner');
            }
        }

        <?php if ($isEditing): ?>
        // Add form submission handling
        document.querySelector('form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = '<?php echo BASE_URL; ?>/admin/partners/detail/<?php echo $partnerId; ?>';
                } else {
                    alert(result.message || 'Error updating partner');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating partner');
            }
        });
        <?php endif; ?>
        </script>
        <?php
    }

    private function getCategories() {
        return $this->partnerController->getCategories();
    }
}
?>