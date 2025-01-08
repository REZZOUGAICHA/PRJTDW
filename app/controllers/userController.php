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

    public function updateUser($id, $data) {
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
                    header('Location: ' . BASE_URL . '/Profile');
                    exit;
                    break;
            }
        }

        require_once __DIR__ . '/../views/userView/profileView.php';
        $view = new ProfileView();
        $view->displayProfile();
    }
}
?>