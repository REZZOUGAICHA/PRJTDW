<?php
class AdminRouter {
    public static function route($page) {
        ob_start();
        require_once __DIR__ . '/../helpers/SessionHelper.php';
        require_once __DIR__ . '/../helpers/HeaderHelper.php';

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
                $partnerController->showPartnerForAdmin();
                break;
            case 'partner':
                require_once __DIR__ . '/../controllers/PartnerController.php';
                $partnerController = new PartnerController();
                if (isset($_GET['id'])) {
                    $partnerController->showPartnerDetail($_GET['id']);
                } else {
                    $partnerController->showPartnerForAdmin();
                }
                break;
            case 'membre':
                // Logic for "membre" will be added here.
                break;

            case 'dons':
                // Logic for "dons" will be added here.
                break;

            case 'benevolat':
                // Logic for "benevolat" will be added here.
                break;

            case 'annonces':
                // Logic for "annonces" will be added here.
                break;

            case 'paiement':
                // Logic for "paiement" will be added here.
                break;

            case 'parametres':
                // Logic for "parametres" will be added here.
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
