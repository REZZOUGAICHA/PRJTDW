<?php

require_once "TableView.php";
require_once __DIR__ . '/../../controllers/navbarController.php';
require_once __DIR__ . '/../../controllers/diapoController.php';
require_once __DIR__ . '/../../controllers/eventController.php';
require_once __DIR__ . '/../../controllers/discountController.php';

class LandingView {

    public function navbarView() {
        try {
            $menuController = new MenuController();
            $menuItems = $menuController->getMenu();  
            
            if (!is_array($menuItems) && !is_object($menuItems)) {
                throw new Exception('Menu items must be an array or object');
            }
            ?>
            <nav class="bg-blue-600 p-4 shadow-md">
                <ul class="flex space-x-6 text-white">
                    <?php
                    foreach ($menuItems as $item) {
                        
                        if (!empty($item['sub_items'])) {
                            echo '<li class="relative group">';  
                            echo '<a href="' . htmlspecialchars($item['link']) . '" class="hover:text-yellow-400 transition duration-300">' . htmlspecialchars($item['name']) . '</a>';
                            
                            echo '<ul class="absolute left-0 hidden mt-2 space-y-2 bg-white text-gray-800 shadow-lg group-hover:block">';
                            foreach ($item['sub_items'] as $subItem) {
                                echo '<li><a href="' . htmlspecialchars($subItem['link']) . '" class="block px-4 py-2 hover:bg-gray-200 transition duration-200">' . htmlspecialchars($subItem['name']) . '</a></li>';
                            }
                            echo '</ul>';
                            echo '</li>';
                        } else {
                            echo '<li><a href="' . htmlspecialchars($item['link']) . '" class="hover:text-yellow-400 transition duration-300">' . htmlspecialchars($item['name']) . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </nav>
            <?php
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo '<nav class="bg-blue-600 p-4 shadow-md"><ul class="flex space-x-6 text-white"><li>Menu unavailable</li></ul></nav>';
        }
    }

    //------------------------------------------------------------------------------------------------------
    public function diaporamaView() {
    $diaporamaController = new DiaporamaController();
    $images = $diaporamaController->getDiaporama();
    ?>
    <div class="big-cont mx-auto mt-16">
        <div class="container mx-auto">
            <div class="inner-container mx-auto">
                <?php if ($images) {
                    foreach ($images as $image) { ?>
                        <img class="bg-contain" src="<?php echo htmlspecialchars($image['lien']); ?>" alt="<?php echo htmlspecialchars($image['titre']); ?>"/>
                    <?php }
                } else {
                    echo "Aucune image trouvée.";
                } ?>
            </div>
        </div>
    </div>
    <style>
        .big-cont { overflow: hidden; }
        .container { overflow: hidden !important; position: relative; width: 400%; }
        .inner-container { display: flex; animation: slide 10s linear infinite; position: relative; gap: 5%; }
        .inner-container:hover { animation-play-state: paused; }
        img { width: 80%; height: 400px; }
        @keyframes slide {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
    </style>
    <?php
}
//------------------------------------------------------------------------------------------------------

    public function eventsView() {
        $eventController = new EventController();
        $data = $eventController->getEvents();
        ?>
        <div class="w-full">
            <?php 
            $this->displaySection($data['events'], 'Latest Events');
            $this->displaySection($data['activities'], 'Latest Activities');
            ?>
        </div>
        <?php
    }
//------------------------------------------------------------------------------------------------------
    private function displaySection($items, $title) {
    if (empty($items)) return;
    ?>
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold mb-6 text-blue-700 capitalize px-4">
            <?php echo $title; ?>
        </h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
            <?php foreach ($items as $item): ?>
                <div class="w-full"> 
                    <?php $this->displayCard($item); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

//------------------------------------------------------------------------------------------------------

    private function displayCard($item) {
        ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden h-full">
            <img 
                src="<?php echo htmlspecialchars($item['file_path']); ?>" 
                alt="<?php echo htmlspecialchars($item['event_name']); ?>"
                class="w-full h-40 object-cover"
            >
            <div class="p-3">
                <h3 class="text-lg font-semibold mb-1">
                    <?php echo htmlspecialchars($item['event_name']); ?>
                </h3>
                <p class="text-gray-600 text-sm mb-1">
                    <?php echo htmlspecialchars($item['event_description']); ?>
                </p>
                <p class="text-xs text-gray-500">
                    <?php echo date('F j, Y g:i A', strtotime($item['event_date'])); ?>
                </p>
            </div>
        </div>
        <?php
    }
//------------------------------------------------------------------------------------------------------
    public function discountsView() {
        $discountController = new DiscountController();
        $data = $discountController->getDiscountsData();
    $columns = [
    ['field' => 'partner_name', 'label' => 'Partner'],
    ['field' => 'category_name', 'label' => 'Category'],
    ['field' => 'card_type_id', 'label' => 'Card Type'],
    ['field' => 'city', 'label' => 'City'],
    

    ];

 if (!class_exists('TableView')) {
        echo 'TableView class not found';
        return;
    }

    $tableView = new TableView();
    $tableView->displayTable($data, $columns);
}
}


?>
