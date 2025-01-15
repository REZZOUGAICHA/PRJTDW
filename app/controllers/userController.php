<?php 
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function registerUser($data) {
        return $this->model->createUser($data);
    }

    public function getUser($id) {
        return $this->model->getUserById($id);
    }
//----------------------admin start---------------------------------
    public function getAllUsers() {
        return [
            'users' => $this->model->getAllUsers()
        ];
    }

    public function getUserInfoById($id) {
        return $this->model->getUserInfoById($id);
    }

    // Add this to UserController class
public function deleteUser($id) {
    try {
        $user = $this->model->getUserById($id);
        if (!$user) {
            throw new Exception('Utilisateur non trouvé');
        }
        
        // Check if we're deleting the currently logged-in user
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
            // Destroy the session for the deleted user
            session_start();
            session_unset();
            session_destroy();
        }
        
        if ($this->model->deleteUser($id)) {
            // Also delete any related sessions in the database if you have a sessions table
            // $this->model->deleteUserSessions($id);
            
            $_SESSION['success'] = 'Utilisateur supprimé avec succès';
            header('Location: ' . BASE_URL . '/admin/membre');
            exit;
        }
        
        throw new Exception('Échec de la suppression');
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: ' . BASE_URL . '/admin/membre');  // Removed the ID from redirect since user might not exist
        exit;
    }
}
//----------------------admin fin---------------------------------
    public function updateUser($id, $data) {//for profile update
        // Validate incoming data
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
            return [
                'error' => 'All fields are required'
            ];
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'error' => 'Invalid email format'
            ];
        }

        // Remove sensitive fields that shouldn't be updated directly
        unset($data['password']);
        unset($data['user_type']);
        
        //  update the user
        $success = $this->model->updateUser($id, $data);
        
        if ($success) {
            return [
                'success' => true,
                'message' => 'Profile updated successfully'
            ];
        }
        
        return [
            'error' => 'Failed to update profile'
        ];
    }

    public function updateUserPassword($id, $currentPassword, $newPassword) {
        return $this->model->updatePassword($id, $currentPassword, $newPassword);
    }

    public function showProfile($id) {
        // Check if there's a POST request for profile update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'update_profile':
                    $result = $this->updateUser($id, $_POST);
                    if (isset($result['error'])) {
                        $_SESSION['error'] = $result['error'];
                    } else {
                        $_SESSION['success'] = $result['message'];
                    }
                    header('Location: ' . BASE_URL . '/profile');
                    exit;
                    break;
            }
        }

        require_once __DIR__ . '/../views/userView/profileView.php';
        $view = new ProfileView();
        $view->displayProfile();
    }

    public function getUserProfilePicture($userId) {
        return $this->model->getProfilePicture($userId);
    }
    


 

    //---------------------display------------------------------
    public function showUsers() {
        require_once __DIR__ . '/../views/adminView/UserView.php';
        $view = new UserView();
        $view->displayUsers();
    }

   
public function showUserDetails($id) {
    error_log("UserController::showUserDetails called with id: " . $id);  // Add this
    $viewPath = __DIR__ . '/../views/adminView/UserDetailsView.php';
    error_log("Attempting to require file: " . $viewPath);  // Add this
    if (!file_exists($viewPath)) {
        error_log("File does not exist: " . $viewPath);  // Add this
        throw new Exception("View file not found");
    }
    require_once $viewPath;
    $view = new UserDetailsView();
    error_log("About to display user detail");  // Add this
    $view->displayUserDetail($id);
    error_log("After displaying user detail");  // Add this
    exit;
}

   
}
?>