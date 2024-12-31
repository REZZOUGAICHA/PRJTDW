<?php
require_once __DIR__ . '/../helpers/Database.php';

class InscriptionModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

     public function createUser($data) {
        $connection = $this->db->connexion();
        
        $query = "INSERT INTO user (first_name, last_name, email, password, profile_picture) 
                 VALUES (:first_name, :last_name, :email, :password, :profile_picture)";
        
        $params = [
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':profile_picture' => $data['profile_picture'] ?? null
        ];

        try {
            // Use PDO::PARAM_LOB for BLOB data
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':first_name', $params[':first_name'], PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $params[':last_name'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $params[':email'], PDO::PARAM_STR);
            $stmt->bindValue(':password', $params[':password'], PDO::PARAM_STR);
            $stmt->bindValue(':profile_picture', $params[':profile_picture'], PDO::PARAM_LOB);
            
            $result = $stmt->execute();
            if ($result) {
                return $connection->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

    public function getUserByEmail($email) {
        $connection = $this->db->connexion();
        
        // First get the user
        $query = "SELECT u.*, 
                    CASE 
                        WHEN m.id IS NOT NULL THEN 'member'
                        ELSE 'user'
                    END as user_type,
                    m.id as member_id,
                    m.card_id
                 FROM user u
                 LEFT JOIN member m ON u.id = m.user_id
                 WHERE u.email = :email AND u.is_active = TRUE";
        
        try {
            $result = $this->db->request($connection, $query, [':email' => $email]);
            $user = $result->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Check if it's a partner (you can add this later)
                // $partnerQuery = "SELECT * FROM partner WHERE user_id = :user_id";
                // $partnerResult = $this->db->request($connection, $partnerQuery, [':user_id' => $user['id']]);
                // if ($partnerResult->fetch()) {
                //     $user['user_type'] = 'partner';
                // }
                
                return $user;
            }
            return false;
        } catch (Exception $e) {
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
                 FROM user u
                 LEFT JOIN member m ON u.id = m.user_id
                 WHERE u.id = :user_id";
                 
        try {
            $result = $this->db->request($connection, $query, [':user_id' => $userId]);
            $data = $result->fetch(PDO::FETCH_ASSOC);
            return $data ? $data['user_type'] : 'user';
        } catch (Exception $e) {
            return 'user';
        } finally {
            $this->db->deconnexion();
        }
    }
}

