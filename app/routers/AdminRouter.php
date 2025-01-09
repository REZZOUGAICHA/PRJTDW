<?php
class AdminRouter {
    public static function route($page) {
        ob_start();
        require_once __DIR__ . '/../helpers/SessionHelper.php';
        require_once __DIR__ . '/../helpers/HeaderHelper.php';
        require_once __DIR__ . '/../helpers/FileUploadHelper.php';

        SessionHelper::init();

        // if (!SessionHelper::isAdminLoggedIn()) {
        //     header('Location: ' . BASE_URL . '/admin/connexion');
        //     exit;
        // }

        require_once __DIR__ . '/../controllers/SidebarController.php';
        HeaderHelper::renderHeader();

        echo '<div class="flex min-h-screen bg-gray-100">';
        $sidebarController = new SidebarController();
        $sidebarController->showSidebar();
        echo '<div class="flex-1 lg:ml-64">';

        switch ($page) {
            case 'tableau-de-bord':
                require_once __DIR__ . '/../controllers/AdminDashboardController.php';
                $dashboardController = new DashboardController();
                $dashboardController->showDashboard();
                break;

            case 'utilisateurs':
                require_once __DIR__ . '/../controllers/AdminUserController.php';
                $userController = new AdminController();
                $userController->listUsers();
                break;

            case 'deconnexion':
                SessionHelper::destroy();
                break;
            
            case 'partenaires':
                
                require_once __DIR__ . '/../controllers/PartnerController.php';
                $partnerController = new PartnerController();
                if (isset($_GET['action']) && $_GET['action'] === 'create') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $partnerController->handlePartnerCreate($_POST, $_FILES);
                    } else {
                        $partnerController->showCreatePartnerForm();
                    }
                } else {
                    $partnerController->showPartnerForAdmin();
                }
                break;
                
                break;
            case 'partner':
                require_once __DIR__ . '/../controllers/PartnerController.php';
                require_once __DIR__ . '/../controllers/offerController.php';
                $partnerController = new PartnerController();
                $offerController = new OfferController();
    
                if (isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'update':
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $success = $partnerController->handlePartnerUpdate($_POST, $_FILES);
                                header('Location: ' . BASE_URL . '/admin/partner?id=' . $_POST['partner_id'] . ($success ? '' : '&edit=true'));
                                exit;
                            }
                break;

            case 'delete':
                if (isset($_GET['id'])) {
                    $partnerController->deletePartner($_GET['id']);
                    header('Location: ' . BASE_URL . '/admin/partenaires');
                    exit;
                }
                break;

            case 'discount/add':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $success = $partnerController->handleDiscountAdd($_POST['partner_id'], $_POST);
                    header('Location: ' . BASE_URL . '/admin/partner?id=' . $_POST['partner_id'] . '&edit=true');
                    exit;
                }
                break;

            case 'discount/update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $success = $partnerController->handleDiscountUpdate($_POST['partner_id'], $_POST);
                    header('Location: ' . BASE_URL . '/admin/partner?id=' . $_POST['partner_id'] . '&edit=true');
                    exit;
                }
                break;

            case 'discount/delete':
                if (isset($_GET['discount_id']) && isset($_GET['partner_id'])) {
                    $success = $partnerController->handleDiscountDelete($_GET['partner_id'], $_GET['discount_id']);
                    header('Location: ' . BASE_URL . '/admin/partner?id=' . $_GET['partner_id'] . '&edit=true');
                    exit;
                }
                break;
                case 'offer/add':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $success = $offerController->handleOfferAdd($_POST['partner_id'], $_POST);
                    header('Location: ' . BASE_URL . '/admin/partner?id=' . $_POST['partner_id'] . '&edit=true');
                    exit;
                }
                break;

            case 'offer/update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $success = $offerController->handleOfferUpdate($_POST['partner_id'], $_POST);
                    header('Location: ' . BASE_URL . '/admin/partner?id=' . $_POST['partner_id'] . '&edit=true');
                    exit;
                }
                break;

            case 'offer/delete':
                if (isset($_GET['offer_id']) && isset($_GET['partner_id'])) {
                    $success = $offerController->handleOfferDelete($_GET['partner_id'], $_GET['offer_id']);
                    header('Location: ' . BASE_URL . '/admin/partner?id=' . $_GET['partner_id'] . '&edit=true');
                    exit;
                }
                break;
            
        }
    } elseif (isset($_GET['id'])) {
        $partnerController->showPartnerDetail($_GET['id']);
    } else {
        $partnerController->showPartnerForAdmin();
    }
    break;
            case 'membre':
                
                break;

            case 'dons':
                require_once __DIR__ . '/../controllers/DonController.php';
                $donController = new DonController();
                $donController->showDonsForAdmin();
                
                break;

            case 'benevolat':
                
                break;
            case 'news':
                require_once __DIR__ . '/../controllers/NewsController.php';
                $newsController = new NewsController();

                if (isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'create':
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $newsController->handleNewsCreate($_POST, $_FILES);
                            } else {
                                $newsController->showCreateNewsForm();
                            }
                            break;

                        case 'update':
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $success = $newsController->handleNewsUpdate($_POST, $_FILES);
                                header('Location: ' . BASE_URL . '/admin/news?id=' . $_POST['news_id'] . ($success ? '' : '&edit=true'));
                                exit;
                            }
                            break;

                        case 'delete':
                            if (isset($_GET['id'])) {
                                $newsController->deleteNews($_GET['id']);
                                header('Location: ' . BASE_URL . '/admin/news');
                                exit;
                            }
                            break;

                        default:
                            echo "Invalid action for news.";
                            break;
                    }
                } else {
                    $newsController->showNewsForAdmin();
                }
                break;


           
            case 'paiement':
                
                break;

            case 'parametres':
                
                break;
            
    

            default:
                echo "Page admin introuvable.";
                break;
        }
        echo '</div></div>';
        HeaderHelper::renderFooter();
        ob_end_flush();
    }
}
?>
