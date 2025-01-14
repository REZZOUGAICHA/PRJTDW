<?php

require_once "TableView.php";
require_once "cardView.php";
require_once "profileImage.php";
//require_once 'LoginPage.php';    

require_once __DIR__ . '/../../controllers/diapoController.php';
require_once __DIR__ . '/../../controllers/eventController.php';
require_once __DIR__ . '/../../controllers/discountController.php';
require_once __DIR__ . '/../../controllers/offerController.php';
require_once __DIR__ . '/../../controllers/topbarController.php';
require_once __DIR__ . '/../../controllers/navbarController.php';
require_once __DIR__ . '/../../helpers/SessionHelper.php';
require_once __DIR__ . '/../../helpers/getImage.php';
require_once __DIR__ . '/../../controllers/UserController.php';



class LandingView {

  
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
    $data = $eventController->getLatestEvents(); // Get 3 latest events
    $cardView = new CardView();
    ?>
    <div class="w-full">
        <?php 
        
        $cardView->displaySection($data['events'], 'Latest Events', [
            'title' => 'event_name',
            'description' => 'event_description',
            'date' => 'event_date',
            'image' => 'file_path',
            'link' => 'link'
        ]);
        
        // Display the latest activities
        $cardView->displaySection($data['activities'], 'Latest Activities', [
            'title' => 'event_name',
            'description' => 'event_description',
            'date' => 'event_date',
            'image' => 'file_path'
        ]);
        ?>
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
    ['field' => 'card_name', 'label' => 'Card Type'],
    ['field' => 'city', 'label' => 'City'],
    ['field' => 'percentage', 'label' => 'percentage'],
    

    ];

 if (!class_exists('TableView')) {
        echo 'TableView class not found';
        return;
    }

    $tableView = new TableView();
    $tableView->displayTable($data, $columns);
}

//------------------------------------------------------------------------------------------------------
public function offersView() {
        $offerController = new offerController();
        $data = $offerController->getOffersData();
    $columns = [
    ['field' => 'offer_name', 'label' => 'Offer'],
    ['field' => 'partner_name', 'label' => 'Partner'],
    ['field' => 'category_name', 'label' => 'Category'],
    ['field' => 'card_name', 'label' => 'Card Type'],
    ['field' => 'start_date', 'label' => 'Start Date'],
    
    

    ];

 if (!class_exists('TableView')) {
        echo 'TableView class not found';
        return;
    }

    $tableView = new TableView();
    $tableView->displayTable($data, $columns);
}

//------------------------------------------------------------------------------------------------------

public function displayTopbar() {
    // controllers
    $topbarController = new topbarController();
    $menuController = new MenuController();

    $topbarData = $topbarController->getTopbarData();
    $socialMediaLinks = $topbarController->getSocialMediaLinks();
    $menuItems = $menuController->getMenu();
    
    // Get session information
    $isLoggedIn = SessionHelper::isLoggedIn();
    $firstName = SessionHelper::get('first_name');
    $userType = SessionHelper::get('user_type');
    $userController = new UserController();
    $userId = htmlspecialchars(SessionHelper::get('user_id'));
    $profilePicture = $userController->getUserProfilePicture($userId);
    ?>
    <!-- Main topbar container -->
    <div class="sticky top-0 z-50 bg-white text-gray-800 shadow-lg border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo  -->
                <div class="flex-shrink-0">
                    <a href="/" class="block">
                        <img src="<?php echo htmlspecialchars($topbarData['logo_link']); ?>" 
                            alt="Logo" 
                            class="h-12 w-auto object-contain">
                    </a>
                </div>

                <!-- Navigation-->
                <nav class="hidden md:flex space-x-8">
                    <?php foreach ($menuItems as $item): ?>
                        <a href="<?php echo htmlspecialchars($item['link']); ?>" 
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-800 hover:bg-blue-50 
                           transition-all duration-200 ease-in-out relative group">
                            <?php echo htmlspecialchars($item['name']); ?>
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-800 transform scale-x-0 
                                       group-hover:scale-x-100 transition-transform duration-200 ease-in-out"></span>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <!-- Auth and Social Media Section -->
                <div class="flex items-center space-x-6">
                    <!-- Social Media Icons  -->
                    <div class="flex items-center space-x-4">
                        <?php foreach ($socialMediaLinks as $link): ?>
                            <a href="<?php echo htmlspecialchars($link['social_media_link']); ?>" 
                                target="_blank"
                                class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200 ease-in-out
                                        transform hover:scale-110 hover:shadow-md">
                                <img src="<?php echo htmlspecialchars($link['icon_link']); ?>" 
                                    alt="<?php echo htmlspecialchars($link['social_media_name']); ?>" 
                                class="w-5 h-5 object-contain">
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- Auth Section -->
                    <div class="hidden md:flex items-center space-x-4">
                        <?php if ($isLoggedIn): ?>
                            <div class="relative group">
                                <button class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium 
                                        text-gray-700 hover:text-blue-800 hover:bg-blue-50 transition-all duration-200">
                                    <span><?php echo htmlspecialchars($firstName); ?></span>
                                    <?php if ($userType === 'member'): ?>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Member</span>
                                    <?php endif; ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-md shadow-lg opacity-0 invisible
                                            group-hover:opacity-100 group-hover:visible transition-all duration-200 ease-in-out">
                                             
                                    <a a href="<?php echo BASE_URL; ?>/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800">
                                        Profile
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>/logout" class="block">
                                    
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            Se déconnecter
                                        </button>
                                    </form>
                                </div>
                            </div><img src="<?php echo htmlspecialchars($profilePicture); ?>" 
                                alt="Profile Picture" 
                            class="w-10 h-10 rounded-full border-2 border-gray-300 object-cover">
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/Connection" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 

                            hover:text-blue-800 hover:bg-blue-50 transition-all duration-200">
                                Login
                            </a>
                            <a href="<?php echo BASE_URL; ?>/Inscription" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 
                                hover:bg-blue-700 transition-all duration-200">
                                Register
                            </a>
                            
                        
                        
            <?php endif; ?>

                    </div>
                </div>

                <!-- Mobile menu button  -->
                <div class="md:hidden">
                    <button type="button" 
                            class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 
                                    focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
//------------------------------------------------------------------------------------------------------
public function announcesView() {
    $newsController = new NewsController();
    $data = $newsController->getAnnounces();
    $cardView = new CardView();
    ?>
    <div class="w-full">
        <?php 
        // announces
        $cardView->displaySection($data['announces'], 'Latest Announcements', [
            'title' => 'name',
            'description' => 'description',
            'image' => 'picture_url',
        ]);
        ?>
    </div>
    <?php
}

public function partnersLogoDisplay() {
    $partnerController = new PartnerController();
    $partners = $partnerController->getPartners();
    ?>
    <h1 class="text-2xl font-bold text-blue-600 mb-8">Nos partenaires</h1>
    <div class="w-full py-8">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($partners as $partner): ?>
                    <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <!-- Partner Logo -->
                        <div class="h-40 w-full flex items-center justify-center mb-4 overflow-hidden">
                            <img 
                                src="<?php echo htmlspecialchars($partner['logo_url']); ?>"
                                alt="<?php echo htmlspecialchars($partner['name']); ?>"
                                class="max-h-32 max-w-full object-contain"
                            >
                        </div>
                        
                        <!-- Partner Name Button -->
                        <!-- add after url website for each partner fl db  -->
                        <button 
                            class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-300 w-full text-center"
                            onclick="window.location.href='<?php echo htmlspecialchars($partner['website_url'] ?? '#'); ?>'"
                            
                        >
                            <?php echo htmlspecialchars($partner['name']); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
}

}?>
