<?php 
require_once __DIR__ . '/../../controllers/submenuController.php';

class SubmenuView {

    private $submenuController;
    
    public function __construct() {
        $this->submenuController = new SubmenuController();
    }
    
    public function displaySubmenu($pageIdentifier = 'partner') {
        $items = $this->submenuController->getSubmenu($pageIdentifier);
        ?> 
        <div class="sticky top-14 z-40 w-full flex justify-center bg-gray-400 py-4">
            <!-- colors here are not being applied dunno why -->
            <div class="inline-flex bg-gray-500 rounded-full shadow-sm px-2">
                <nav class="flex items-center h-10">
                    <?php foreach ($items as $item): ?>
                        <a href="<?php echo htmlspecialchars($item['link']); ?>" 
                            class="px-6 py-1 text-sm font-medium text-gray-700 hover:text-blue-800 
                            rounded-full hover:bg-blue-50 transition-all duration-200 ease-in-out
                            <?php echo $this->submenuController->isCurrentPage($item['link']) ? 'text-blue-800 bg-blue-50' : ''; ?>">
                            <?php echo htmlspecialchars($item['item_name']); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>
        <?php
        
    }
}
?>