<?php
require_once __DIR__ . '/../../controllers/EntityViewController.php';

class EntityView {
    private $controller;

    public function __construct() {
        $this->controller = new EntityViewController();
    }

    public function displayEntity($link, $tableName) {
        // Get entity details based on the provided link and table name
        $entity = $this->controller->getEntityDetails($link, $tableName);
        
        if (!$entity) {
            echo '<div class="p-4 bg-red-100 text-red-700 rounded">Entity not found</div>';
            return;
        }

        ?>
        <div class="max-w-4xl mx-auto p-6">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Entity Header -->
                <div class="p-6 bg-gray-50 border-b">
                    <h1 class="text-2xl font-bold text-gray-900">
                        <?php echo htmlspecialchars($entity['name'] ?? 'Details'); ?>
                    </h1>
                </div>

                <!-- Entity Details -->
                <div class="p-6 space-y-4">
                    <?php foreach ($entity as $key => $value): ?>
                        <?php if ($key !== 'id' && $key !== 'password' && $key !== 'user_id'): ?>
                            <div class="flex border-b pb-4">
                                <div class="w-1/3 font-medium text-gray-600">
                                    <?php echo ucfirst(str_replace('_', ' ', $key)); ?>
                                </div>
                                <div class="w-2/3 text-gray-900">
                                    <?php if ($key === 'logo_url' && filter_var($value, FILTER_VALIDATE_URL)): ?>
                                        <img src="<?php echo htmlspecialchars($value); ?>" 
                                             alt="Logo" 
                                             class="max-w-xs h-auto rounded">
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($value); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
