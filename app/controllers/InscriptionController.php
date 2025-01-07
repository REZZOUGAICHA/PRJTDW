<?php
require_once __DIR__ . '/../models/InscriptionModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';

class InscriptionController {
    private $inscriptionModel;
    private $fileUploadHelper;

    public function __construct() {
        $this->inscriptionModel = new InscriptionModel();
        $this->fileUploadHelper = new FileUploadHelper();
        SessionHelper::init();
    }

    public function handleInscription($data, $files = null) {
        // Validate required fields
        if (empty($data['first_name']) || empty($data['last_name']) || 
            empty($data['email']) || empty($data['password'])) {
            return ['error' => 'All fields are required'];
        }

        // Hash password before saving
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Handle profile picture if uploaded
        if (isset($files['profile_picture']) && $files['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->fileUploadHelper->uploadFile($files['profile_picture']);
            
            if ($uploadResult['success']) {
                $data['profile_picture'] = $uploadResult['fileContent'];
            } else {
                return ['error' => $uploadResult['error']];
            }
        }

        try {
            $userId = $this->inscriptionModel->createUser($data);
            
            if ($userId) {
                // Set session data
                SessionHelper::set('user_id', $userId);
                SessionHelper::set('user_type', 'user');
                SessionHelper::set('first_name', $data['first_name']);
                SessionHelper::set('last_name', $data['last_name']);
                
                return ['success' => 'User registered successfully', 'redirect' => '/accueil'];
            }
        } catch (Exception $e) {
            // Handle specific errors (e.g., duplicate email)
            return ['error' => 'Registration failed: ' . $e->getMessage()];
        }

        return ['error' => 'Registration failed'];
    }

    public function handleLogin($email, $password) {
        if (empty($email) || empty($password)) {
            return ['error' => 'Email and password are required'];
        }

        try {
            $user = $this->inscriptionModel->getUserByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                // Set basic session data
                SessionHelper::set('user_id', $user['id']);
                SessionHelper::set('user_type', $user['user_type']);
                SessionHelper::set('first_name', $user['first_name']);
                SessionHelper::set('last_name', $user['last_name']);
                
                // Set additional data for members
                if ($user['user_type'] === 'member') {
                    SessionHelper::set('member_id', $user['member_id']);
                    SessionHelper::set('card_id', $user['card_id']);
                }
                
                // Determine redirect based on user type
                $redirect = match($user['user_type']) {
                    'member' => '/accueil',
                    'partner' => '/accueil',
                    default => '/accueil'
                };
                
                return ['success' => 'Login successful', 'redirect' => $redirect];
            }
        } catch (Exception $e) {
            return ['error' => 'Login failed: ' . $e->getMessage()];
        }
        
        return ['error' => 'Invalid email or password'];
    }

    public function showInscriptionForm() {
        require_once __DIR__ . '/../views/userView/InscriptionView.php';
        $view = new InscriptionView();
        $view->displayInscriptionForm();
    }

    public function showLoginForm() {
        require_once __DIR__ . '/../views/userView/InscriptionView.php';
        $view = new InscriptionView();
        $view->displayLoginForm();
    }

    public function logout() {
        SessionHelper::destroy();
        header('Location: ' . BASE_URL . '/accueil');
        exit;
    }
}