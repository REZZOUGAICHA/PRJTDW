<?php
class Router {
    public static function route($page) {
        ob_start();
        require_once __DIR__ . '/../helpers/SessionHelper.php';
        require_once __DIR__ . '/../helpers/HeaderHelper.php';
        
        SessionHelper::init();
        
        // Common controllers
        require_once __DIR__ . '/../controllers/topbarController.php';
        require_once __DIR__ . '/../controllers/FooterController.php';
        
        $topbarController = new topbarController();
        $footer = new FooterController();
        
        // Public routes - accessible to everyone
        $publicRoutes = ['acceuil', 'partenaires', 'Remises', 'Connection', 'Inscription','Don', 'Aide', 'News'];
        
        // Protected routes - require authentication
        $protectedRoutes = ['Profile'];
        
        $segments = explode('/', $page);
        $currentRoute = $segments[0];
        
        // Check authentication for protected routes
        if (in_array($currentRoute, $protectedRoutes) && !SessionHelper::isLoggedIn()) {
            $_SESSION['redirect_after_login'] = '/' . $currentRoute;
            header('Location: ' . BASE_URL . '/Connection');
            exit;
        }
        HeaderHelper::renderHeader();
        
        // Display topbar on all pages
        $topbarController->showTopbar();
        
        
        switch ($currentRoute) {
            case 'acceuil':
                
                
                require_once __DIR__ . '/../controllers/diapoController.php';
                require_once __DIR__ . '/../controllers/EventController.php';
                require_once __DIR__ . '/../controllers/discountController.php';
                require_once __DIR__ . '/../controllers/offerController.php';
                
                
                
                $diapoController = new DiaporamaController();
                $diapoController->showDiaporama();
                $EventController = new EventController();
                $EventController->showEvent();
                $discountController = new discountController();
                $discountController->showDiscount();
                $offerController = new offerController();
                $offerController->showOffer();
                
                
                
                break;

            case 'partenaires':
                
                require_once __DIR__ . '/../controllers/partnerController.php';
                
                require_once __DIR__ . '/../controllers/SubmenuController.php';
                

                if (isset($_GET['ajax'])) {
                    // Example: Return JSON data for AJAX
                    $offresController = new OffresController();
                    $offresController->getOffers();
                } else {
                    
                    $submenu = new SubmenuController();
                    $submenu->showSubmenu('partner');
                    $partnerController = new partnerController();
                    $partnerController->showPartnerForUser();
                    

                }
                break;

            case 'Remises':
                
                require_once __DIR__ . '/../controllers/partnerController.php';
                require_once __DIR__ . '/../controllers/SubmenuController.php';
                require_once __DIR__ . '/../controllers/discountController.php';

                    
                    $submenu = new SubmenuController();
                    $submenu->showSubmenu('offer');
                    $offer = new offerController();
                    $offer->showoffer();
                    $discount = new discountController();
                    $discount->showdiscount();
                    
                break;
                case 'Don':
                

                    
                break;
                case 'Aide':
                
                

                    
                break;
                case 'News':
                
                

                    
                break;
                case 'Profile':
                require_once __DIR__ . '/../controllers/UserController.php';
                $userController = new UserController();
                $userController->showProfile(SessionHelper::get('user_id'));
                
                

                    
                break;
                case 'Connection':
                    require_once __DIR__ . '/../controllers/InscriptionController.php';
                $inscriptionController = new InscriptionController();
                
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $result = $inscriptionController->handleLogin($_POST['email'], $_POST['password']);
                    if (isset($result['success'])) {
                        
                        header('Location: ' . BASE_URL . $result['redirect']);
                        exit;
                    }
                    // Handle error case
                    SessionHelper::set('error', $result['error']);
                }
                
                
                $inscriptionController->showLoginForm();
                
                
                

                break;
                    
                case 'Inscription':
                    require_once __DIR__ . '/../controllers/InscriptionController.php';
                $inscriptionController = new InscriptionController();
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $result = $inscriptionController->handleInscription($_POST, $_FILES);
                    if (isset($result['success'])) {
                        header('Location: ' . BASE_URL . $result['redirect']);
                        exit;
                    }
                    // Handle error case
                    // You might want to store the error in session and display it in the form
                    SessionHelper::set('error', $result['error']);
                }

                

                    
                    $inscriptionController->showInscriptionForm();
                    
                break;
                case 'logout':
                require_once __DIR__ . '/../controllers/InscriptionController.php';
                $inscriptionController = new InscriptionController();
                $inscriptionController->logout();
                break;

                case 'membership' : 
                require_once __DIR__ . '/../controllers/membershipController.php';
                $membershipController = new membershipController();
                $membershipController->showMembership();
                break;
                

            default:
                echo "Page not found.";
                break;
        }
        HeaderHelper::renderFooter();
        $footer->showFooter();
        ob_end_flush();
    }
    
}
