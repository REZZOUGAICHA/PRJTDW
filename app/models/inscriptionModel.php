<?php 
require_once __DIR__ . '/../helpers/Database.php';
//login not working :(((((((())))))))

class InscriptionModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createUser($data) {
        $connection = $this->db->connexion();
        
        $query = "INSERT INTO `user` (first_name, last_name, email, password, profile_picture, user_type) 
                VALUES (:first_name, :last_name, :email, :password, :profile_picture, 'user')";
        
        $params = [
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => $data['password'], 
            ':profile_picture' => $data['profile_picture'] ?? null
        ];

        try {
            $stmt = $this->db->request($connection, $query, $params);
            if ($stmt) {
                return $connection->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            // Log the error for debugging
            error_log("Error creating user: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

    public function getUserByEmail($email) {
    $connection = $this->db->connexion();
    
    $query = "SELECT 
                u.*,
                'user' as user_type
             FROM `user` u
             WHERE u.email = :email";
    
    try {
        $stmt = $this->db->request($connection, $query, [':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Add debug logging
        error_log("User data fetched: " . print_r($user, true));
        
        return $user ?: false;
    } catch (Exception $e) {
        error_log("Error getting user by email: " . $e->getMessage());
        return false;
    } finally {
        $this->db->deconnexion();
    }
}


    public function checkUserType($userId) {
        $connection = $this->db->connexion();
        
        $query = "SELECT 
                    CASE 
                WHEN m.id IS NOT NULL THEN 'member'
                ELSE 'user'
                END as user_type
                FROM `user` u
                LEFT JOIN member m ON u.id = m.user_id
                WHERE u.id = :user_id";
            
        try {
            $stmt = $this->db->request($connection, $query, [':user_id' => $userId]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? $data['user_type'] : 'user';
        } catch (Exception $e) {
            error_log("Error checking user type: " . $e->getMessage());
            return 'user';
        } finally {
            $this->db->deconnexion();
        }
    }
}
?>
