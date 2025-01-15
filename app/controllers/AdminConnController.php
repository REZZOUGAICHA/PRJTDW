<?php
require_once __DIR__ . '/../models/AdminConnModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class AdminConnController {
    private $adminModel;

    public function __construct() {
        $this->adminModel = new AdminConnModel();
        SessionHelper::init();
    }

    public function handleLogin($name, $password) {
        error_log("HandleLogin called with name: " . $name . " and password: " . $password);

        if (empty($name) || empty($password)) {
            error_log("Name or password empty");
            return ['error' => 'Name and password are required'];
        }

        try {
            error_log("Attempting to get admin by name: " . $name);
            $admin = $this->adminModel->getAdminByName($name);
            error_log("Admin data retrieved: " . print_r($admin, true));

            if (!$admin) {
                error_log("No admin found with name: " . $name);
                return ['error' => 'Invalid name or password'];
            }

            error_log("Comparing passwords - Input: " . $password . ", Stored: " . $admin['password']);
            
            // Check if admin exists and compare passwords directly
            if ($password === $admin['password']) {
                error_log("Password match successful");
                
                // Set session data
                SessionHelper::set('admin_id', $admin['id']);
                SessionHelper::set('admin_name', $admin['name']);
                SessionHelper::set('admin_type', $admin['admin_type']);
                SessionHelper::set('admin_type_name', $admin['admin_type_name']);
                SessionHelper::set('is_admin', true);

                error_log("Session data set successfully: " . print_r($_SESSION, true));
                
                error_log("Redirecting to: " . BASE_URL . '/admin/tableau-de-bord');
                header('Location: ' . BASE_URL . '/admin/tableau-de-bord');
                exit;
            }
            
            error_log("Password verification failed - passwords don't match");
            return ['error' => 'Invalid name or password'];
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return ['error' => 'Login failed: ' . $e->getMessage()];
        }
    }

    public function showLoginForm() {
        require_once __DIR__ . '/../views/adminView/AdminConnView.php';
        $view = new AdminConnView();
        $view->displayLoginForm();
    }

    public function logout() {
        SessionHelper::destroy();
        header('Location: ' . BASE_URL . '/admin/login');
        exit;
    }
}
?>