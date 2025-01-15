<?php
class AdminRouter {
    public static function route($page) {
        ob_start();
        require_once __DIR__ . '/../helpers/SessionHelper.php';
        require_once __DIR__ . '/../helpers/HeaderHelper.php';
        require_once __DIR__ . '/../helpers/FileUploadHelper.php';

        SessionHelper::init();

        if (!isset($_SESSION['admin_id'])) {
            $_SESSION['admin_id'] = 1; 
            $_SESSION['is_admin'] = true;
        }

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
    require_once __DIR__ . '/../controllers/UserController.php';
    $userController = new UserController();

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'create':
                // Future implementation for creating a new member (to be implemented later)
                break;
            case 'update':
                // Future implementation for updating a member (to be implemented later)
                break;
            case 'delete':
                // Future implementation for deleting a member (to be implemented later)
                break;
            default:
                // If no specific action, just show the list of users
                $userController->showUsers();
                break;
        }
    } else {
        // If no specific action, just show the list of users
        $userController->showUsers();
    }
    break;

            case 'aid':
    require_once __DIR__ . '/../controllers/AidController.php';
    $aidController = new AidController();

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'accept':
                if (isset($_GET['id'])) {
                    // Debugging statement
                    error_log("Accepting aid request with ID: " . $_GET['id']);
                    $aidController->acceptRequest($_GET['id']);
                    header('Location: ' . BASE_URL . '/admin/aide');
                    exit;
                }
                break;

            case 'refuse':
                if (isset($_GET['id'])) {
                    // Debugging statement
                    error_log("Refusing aid request with ID: " . $_GET['id']);
                    $aidController->refuseRequest($_GET['id']);
                    header('Location: ' . BASE_URL . '/admin/aide');
                    exit;
                }
                break;

            case 'view':
                if (isset($_GET['id'])) {
                    $aidController->showAidRequestDetails($_GET['id']);
                    exit;
                }
                break;

            default:
                header('Location: ' . BASE_URL . '/admin/aide');
                exit;
        }
    } else {
        $aidController->showAidRequests();
    }
    break;

            case 'dons':
    require_once __DIR__ . '/../controllers/DonController.php';
    $donController = new DonController();

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'accept':
                if (isset($_GET['id'])) {
                    $donController->acceptDon($_GET['id']);
                    header('Location: ' . BASE_URL . '/admin/dons');
                    exit;
                }
                break;

            case 'refuse':
                if (isset($_GET['id'])) {
                    $donController->refuseDon($_GET['id']);
                    header('Location: ' . BASE_URL . '/admin/dons');
                    exit;
                }
                break;

            case 'view':
                if (isset($_GET['id'])) {
                    $donController->showDonDetails($_GET['id']);
                    exit;
                }
                break;

            default:
                // Rediriger ou gérer les actions inconnues
                header('Location: ' . BASE_URL . '/admin/dons');
                exit;
        }
    } else {
        // Vue par défaut : afficher la liste des dons
        $donController->showDonsForAdmin();
    }
    break;

                
                break;

            case 'benevolat':
                
                break;
            case 'membre':
    require_once __DIR__ . '/../controllers/UserController.php';
    $userController = new UserController();

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'create':
                // Future implementation for creating a new member
                break;
            case 'update':
                // Future implementation for updating a member
                break;
            case 'delete':
                // Future implementation for deleting a member
                break;
            default:
                // If no specific action, just show the list of users
                $userController->showUsersList();
                break;
        }
    } else {
        // If no specific action, just show the list of users
        $userController->showUsersList();
    }
    break;


           
            case 'paiement':
                
                break;

            case 'parametres':
                
                break;
            case 'adhesions':
                require_once __DIR__ . '/../controllers/MembershipController.php';
                $membershipController = new MembershipController();

                if (isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'accept':
                            if (isset($_GET['id'])) {
                                $membershipController->acceptRequest($_GET['id']);
                                header('Location: ' . BASE_URL . '/admin/adhesions');
                                exit;
                            }
                            break;

                        case 'refuse':
                            if (isset($_GET['id'])) {
                                $membershipController->refuseRequest($_GET['id']);
                                header('Location: ' . BASE_URL . '/admin/adhesions');
                                exit;
                            }
                            break;

                        case 'view':
                            if (isset($_GET['id'])) {
                                $membershipController->showMembershipDetails($_GET['id']);
                                exit;
                            }
                            break;

                        default:
                            // Redirect or handle unknown actions
                            header('Location: ' . BASE_URL . '/admin/adhesions');
                            exit;
                    }
                } else {
                    // Default view: list all membership applications
                    $membershipController->showMembershipApplications();
                }
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
