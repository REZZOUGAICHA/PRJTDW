<?php
class AdminRouter {
    public static function route($page) {
        ob_start();
        require_once __DIR__ . '/../helpers/SessionHelper.php';
        require_once __DIR__ . '/../helpers/HeaderHelper.php';

        SessionHelper::init();

        // Inclusion des contrôleurs pour l'administration
        require_once __DIR__ . '/../controllers/SidebarController.php';

        // Vérification de l'authentification
        // if (!SessionHelper::isLoggedIn('admin')) {
        //     header('Location: ' . BASE_URL . '/admin/connexion');
        //     exit;
        // }
        HeaderHelper::renderHeader();
        // Affichage de la barre latérale sur toutes les pages admin
        $sidebarController = new SidebarController();
        $sidebarController->showSidebar();

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
                SessionHelper::destroy('admin');
                header('Location: ' . BASE_URL . '/admin/connexion');
                exit;
                break;
            
            case 'partenaires':
                require_once __DIR__ . '/../controllers/PartnerController.php';
                $partnerController = new PartnerController();
                $partnerController->showPartnerForAdmin();
                break;

            default:
                echo "Page admin introuvable.";
                break;
        }
        HeaderHelper::renderFooter();
        ob_end_flush();
    }
}
