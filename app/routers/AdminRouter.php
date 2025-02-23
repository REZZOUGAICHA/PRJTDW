<?php
error_log("Starting route processing");
error_log("Current working directory: " . getcwd());
error_log("Full request URL: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

class AdminRouter {
    public static function route($page) {
        ob_start();
        require_once __DIR__ . '/../helpers/SessionHelper.php';
        require_once __DIR__ . '/../helpers/HeaderHelper.php';
        require_once __DIR__ . '/../helpers/FileUploadHelper.php';

        SessionHelper::init();
        $isLoginPage = ($page === 'connection');
        $isLoggedIn = isset($_SESSION['admin_id']) && $_SESSION['is_admin'] === true;

        // If not logged in and not on login page, redirect to login
        if (!$isLoggedIn && !$isLoginPage) {
            header('Location: ' . BASE_URL . '/admin/connection');
            exit;
        }

        // If logged in and trying to access login page, redirect to dashboard
        if ($isLoggedIn && $isLoginPage) {
            header('Location: ' . BASE_URL . '/admin/tableau-de-bord');
            exit;
        }

        // Only render header and sidebar if logged in
        if ($isLoggedIn) {
            require_once __DIR__ . '/../controllers/SidebarController.php';
            HeaderHelper::renderHeader();
            echo '<div class="flex min-h-screen bg-gray-100">';
            $sidebarController = new SidebarController();
            $sidebarController->showSidebar();
            echo '<div class="flex-1 lg:ml-64">';
        }

        

        

        

        switch ($page) {
            case 'test-logout':
    session_destroy();
    session_unset();
    header('Location: ' . BASE_URL . '/admin/connection');
    exit;
    break;
            
            case 'connection':
    require_once __DIR__ . '/../controllers/AdminConnController.php';
    $adminConnController = new AdminConnController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log("Processing login POST request");
        $result = $adminConnController->handleLogin($_POST['name'], $_POST['password']);
        error_log("Login result: " . print_r($result, true));
    } else {
        error_log("Showing login form");
        $adminConnController->showLoginForm();
    }
    break;

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
                                $newsController->handleNewsUpdate($_POST['news_id'], $_POST, $_FILES);
                                header('Location: ' . BASE_URL . '/admin/news?id=' . $_POST['news_id']);
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
                    }
                } elseif (isset($_GET['id'])) {
                    $newsController->showNewsDetails($_GET['id']);
                } else {
                    $newsController->showNewsForAdmin();
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

    try {
        error_log("Entering membre case");
        if (isset($_GET['action'])) {
            error_log("Action is set: " . $_GET['action']);
            switch ($_GET['action']) {
                case 'delete':
                    if (isset($_GET['id'])) {
                        error_log("Deleting user with ID: " . $_GET['id']);
                        $userController->deleteUser($_GET['id']);
                        $_SESSION['success'] = "Utilisateur supprimé avec succès.";
                        header('Location: ' . BASE_URL . '/admin/membre');
                        exit;
                    } else {
                        error_log("Delete action requested but no ID provided.");
                        $_SESSION['error'] = "ID de l'utilisateur non spécifié pour la suppression.";
                        header('Location: ' . BASE_URL . '/admin/membre');
                        exit;
                    }
                    break;
                // Add other actions here as needed
            }
        } elseif (isset($_GET['id'])) {
            error_log("About to show user details for ID: " . $_GET['id']);
            $userController->showUserDetails($_GET['id']);
            exit;
        } else {
            error_log("Showing all users");
            $userController->showUsers();
        }
    } catch (Exception $e) {
        error_log("Error in membre case: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header('Location: ' . BASE_URL . '/admin/membre');
        exit;
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
        if ($isLoggedIn) {
            echo '</div></div>';
            HeaderHelper::renderFooter();
        }
        ob_end_flush();
    }
}
?>
