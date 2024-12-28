<?php

class CardView {
    
    public function displayCard($item, $config = []) {
    $titleKey = $config['title'] ?? 'title'; // Default key for title
    $descriptionKey = $config['description'] ?? 'description'; // Default key for description
    $dateKey = $config['date'] ?? 'date'; // Default key for date
    $imageKey = $config['image'] ?? 'image_url'; // Default key for image

    ?>
    <div class="bg-white rounded-lg shadow-lg overflow-hidden h-full">
        <img 
            src="<?php echo htmlspecialchars($item[$imageKey] ?? ''); ?>" 
            alt="<?php echo htmlspecialchars($item[$titleKey] ?? ''); ?>"
            class="w-full h-40 object-cover"
        >
        <div class="p-3">
            <h3 class="text-lg font-semibold mb-1">
                <?php echo htmlspecialchars($item[$titleKey] ?? ''); ?>
            </h3>
            <p class="text-gray-600 text-sm mb-1">
                <?php echo htmlspecialchars($item[$descriptionKey] ?? ''); ?>
            </p>
            <?php if (!empty($item[$dateKey])): ?>
                <p class="text-xs text-gray-500">
                    <?php echo date('F j, Y g:i A', strtotime($item[$dateKey])); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

public function displaySection($items, $title, $config = []) {
    if (empty($items)) return;
    ?>
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold mb-6 text-blue-700 capitalize px-4">
            <?php echo $title; ?>
        </h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
            <?php foreach ($items as $item): ?>
                <div class="w-full"> 
                    <?php 
                    // Pass the item and the configuration to the displayCard method
                    $this->displayCard($item, $config);
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

}
?>