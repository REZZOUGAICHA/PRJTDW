<?php
require_once __DIR__ . '/../models/InscriptionModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';
require_once __DIR__ . '/../helpers/FileUploadHelper.php';
require_once __DIR__ . '/../models/partnerModel.php';

class InscriptionController {
    private $inscriptionModel;
    private $fileUploadHelper;
    private $partnerModel;

    public function __construct() {
        $this->inscriptionModel = new InscriptionModel();
        $this->fileUploadHelper = new FileUploadHelper();
        $this->partnerModel = new PartnerModel();
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
//------------------------------------------------------user-------------------------------------------------------
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
//------------------------------------------------------partner-------------------------------------------------------
public function handlePartnerLogin($email, $password) {
    if (empty($email) || empty($password)) {
        return ['error' => 'Email et mot de passe sont requis'];
    }

    try {
        $user = $this->inscriptionModel->getUserByEmail($email);
        
        // Check if user exists and is a partner
        if ($user && $user['user_type'] === 'partner' && password_verify($password, $user['password'])) {
            // Get partner details based on user_id
            $partner = $this->partnerModel->getPartnerByUserId($user['id']);
            
            if (!$partner) {
                return ['error' => 'Compte partenaire non trouvé'];
            }

            // Set session data
            SessionHelper::set('user_id', $user['id']);
            SessionHelper::set('user_type', 'partner');
            SessionHelper::set('first_name', $user['first_name']);
            SessionHelper::set('last_name', $user['last_name']);
            SessionHelper::set('partner_id', $partner['id']);
            SessionHelper::set('partner_name', $partner['name']);
            
            return [
                'success' => 'Connexion réussie', 
                'redirect' => '/partner/dashboard'  // Or whatever your partner dashboard route is
            ];
        }

        // If user exists but is not a partner
        if ($user && $user['user_type'] !== 'partner') {
            return ['error' => 'Ce compte n\'est pas un compte partenaire'];
        }
        
    } catch (Exception $e) {
        return ['error' => 'Échec de la connexion: ' . $e->getMessage()];
    }
    
    return ['error' => 'Email ou mot de passe invalide'];
}
//----------------------------------------------------------------------------------------------------------------------

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