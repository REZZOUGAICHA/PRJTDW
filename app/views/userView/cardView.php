<?php 
class CardView { 
    public function displayCard($item, $config = []) {
    // diff configs (config is optional)
    $titleKey = $config['title'] ?? 'title';
    $descriptionKey = $config['description'] ?? 'description';
    $dateKey = $config['date'] ?? 'date';
    $imageKey = $config['image'] ?? 'image_url';
    $linkKey = $config['link'] ?? 'link';
    $discountsKey = $config['discounts'] ?? null; 
    $extraFields = $config['extraFields'] ?? []; // For offers 

    ?> 
    <!-- element class cus used in the search js func -->
    <div class="element bg-white rounded-lg shadow-lg overflow-hidden h-full flex flex-col"> 
        <?php if (!empty($item[$imageKey])): ?> 
        <img  
            src="<?php echo htmlspecialchars($item[$imageKey]); ?>"  
            alt="<?php echo htmlspecialchars($item[$titleKey] ?? ''); ?>" 
            class="w-full h-40 object-cover" 
        >
        <?php endif; ?> 
        <div class="p-3 flex-grow flex flex-col"> 
            
            <h3 class="element-name text-lg font-semibold mb-1"> 
                <?php echo htmlspecialchars($item[$titleKey] ?? ''); ?> 
            </h3> 
            <p class="text-gray-600 text-sm mb-1"> 
                <?php echo htmlspecialchars($item[$descriptionKey] ?? ''); ?> 
            </p> 
            <!-- offers -->
            <?php foreach ($extraFields as $label => $key): ?>
                <p class="text-sm text-gray-700">
                    <strong><?php echo $label; ?>:</strong> 
                    <?php echo htmlspecialchars($item[$key] ?? ''); ?>
                </p>
            <?php endforeach; ?>

            <!-- Discounts  -->
            <?php if ($discountsKey && !empty($item[$discountsKey])): ?>
                <ul class="text-sm text-gray-700 mb-3">
                    <?php foreach ($item[$discountsKey] as $discount): ?>
                        <li>
                            <?php echo htmlspecialchars($discount['name'] ?? ''); ?> - 
                            <?php echo htmlspecialchars($discount['percentage'] ?? ''); ?>%
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <!-- Voir plus button -->
            <div class="mt-2 flex justify-start">
                <a href="<?php echo htmlspecialchars($item[$linkKey] ?? '#'); ?>" 
                    class="inline-block bg-blue-600 text-white px-4 py-1 rounded-md 
                    hover:bg-blue-700 transition-colors duration-200 text-sm font-medium
                    hover:shadow-md">
                    Voir plus
                </a>
            </div>
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
