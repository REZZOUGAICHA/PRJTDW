<?php
class Router
{
    public static function route($page)
    {
        // Split the page parameter into segments
        $segments = explode('/', $page);
        require_once __DIR__ . '/../controllers/topbarController.php';
        require_once __DIR__ . '/../controllers/FooterController.php';
        switch ($segments[0]) {
            case 'acceuil':
                
                require_once __DIR__ . '/../controllers/topbarController.php';
                require_once __DIR__ . '/../controllers/diapoController.php';
                require_once __DIR__ . '/../controllers/EventController.php';
                require_once __DIR__ . '/../controllers/discountController.php';
                require_once __DIR__ . '/../controllers/offerController.php';
                require_once __DIR__ . '/../controllers/FooterController.php';

                $topbarController = new topbarController();
                $topbarController->showTopbar();
                $diapoController = new DiaporamaController();
                $diapoController->showDiaporama();
                $EventController = new EventController();
                $EventController->showEvent();
                $discountController = new discountController();
                $discountController->showDiscount();
                $offerController = new offerController();
                $offerController->showOffer();
                $footer = new FooterController();
                $footer->showFooter();
                
                
                break;

            case 'partenaires':
                require_once __DIR__ . '/../controllers/topbarController.php';
                require_once __DIR__ . '/../controllers/partnerController.php';
                require_once __DIR__ . '/../controllers/FooterController.php';
                require_once __DIR__ . '/../controllers/SubmenuController.php';
                

                if (isset($_GET['ajax'])) {
                    // Example: Return JSON data for AJAX
                    $offresController = new OffresController();
                    $offresController->getOffers();
                } else {
                    $topbarController = new topbarController();
                    $topbarController->showTopbar();
                    $submenu = new SubmenuController();
                    $submenu->showSubmenu('partner');
                    $partnerController = new partnerController();
                    $partnerController->showPartnerForUser();
                    $footer = new FooterController();
                    $footer->showFooter();

                }
                break;

            case 'Remises':
                require_once __DIR__ . '/../controllers/topbarController.php';
                require_once __DIR__ . '/../controllers/partnerController.php';
                require_once __DIR__ . '/../controllers/FooterController.php';
                require_once __DIR__ . '/../controllers/SubmenuController.php';
                require_once __DIR__ . '/../controllers/discountController.php';

                    $topbarController = new topbarController();
                    $topbarController->showTopbar();
                    $submenu = new SubmenuController();
                    $submenu->showSubmenu('offer');
                    $offer = new offerController();
                    $offer->showoffer();
                    $discount = new discountController();
                    $discount->showdiscount();
                    $footer = new FooterController();
                    $footer->showFooter();
                break;
                case 'Don':
                

                    $topbarController = new topbarController();
                    $topbarController->showTopbar();
                    $footer = new FooterController();
                    $footer->showFooter();
                break;
                case 'Aide':
                
                

                    $topbarController = new topbarController();
                    $topbarController->showTopbar();
                    
                    $footer = new FooterController();
                    $footer->showFooter();
                break;
                case 'News':
                
                

                    $topbarController = new topbarController();
                    $topbarController->showTopbar();
                    
                    $footer = new FooterController();
                    $footer->showFooter();
                break;
                case 'Profile':
                
                

                    $topbarController = new topbarController();
                    $topbarController->showTopbar();
                    
                    $footer = new FooterController();
                    $footer->showFooter();
                break;
                case 'Connection':
                    require_once __DIR__ . '/../controllers/InscriptionController.php';
                $inscriptionController = new InscriptionController();
                $topbarController = new topbarController();
                $footer = new FooterController();
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $result = $inscriptionController->handleLogin($_POST['email'], $_POST['password']);
                    if (isset($result['success'])) {
                        header('Location: ' . $result['redirect']);
                        exit;
                    }
                    // Handle error case
                    SessionHelper::set('error', $result['error']);
                }
                
                $topbarController->showTopbar();
                $inscriptionController->showLoginForm();
                $footer->showFooter();
                
                

                break;
                    
                case 'Inscription':
                    require_once __DIR__ . '/../controllers/InscriptionController.php';
                $inscriptionController = new InscriptionController();
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $result = $inscriptionController->handleInscription($_POST, $_FILES);
                    if (isset($result['success'])) {
                        header('Location: ' . $result['redirect']);
                        exit;
                    }
                    // Handle error case
                    // You might want to store the error in session and display it in the form
                    SessionHelper::set('error', $result['error']);
                }

                

                    $topbarController = new topbarController();
                    $topbarController->showTopbar();
                    $inscriptionController->showInscriptionForm();
                    $footer = new FooterController();
                    $footer->showFooter();
                break;
                case 'logout':
                require_once __DIR__ . '/../controllers/InscriptionController.php';
                $inscriptionController = new InscriptionController();
                $inscriptionController->logout();
                break;
                

            default:
                echo "Page not found.";
                break;
        }
    }
}
