<?php
require_once __DIR__ . '/../helpers/Database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($data) {
        $connection = $this->db->connexion();
        $query = "INSERT INTO user (first_name, last_name, email, password, profile_picture, is_active) 
                    VALUES (:first_name, :last_name, :email, :password, :profile_picture, :is_active)";
        $params = [
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':profile_picture' => $data['profile_picture'],
            ':is_active' => 0 ,
            'user_type' => $data['user_type']
        ];
        try {
            $this->db->request($connection, $query, $params);
            return $connection->lastInsertId();
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

    public function getUserById($id) {
        $connection = $this->db->connexion();
        $query = "SELECT id, first_name, last_name, email, registration_date, is_active,user_type
                FROM user 
                WHERE id = :id";
        $params = [':id' => $id];
        
        try {
            $stmt = $this->db->request($connection, $query, $params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: false;
        } catch (Exception $e) {
            error_log("Error getting user: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

    public function getUserByEmail($email) {
        $connection = $this->db->connexion();
        $query = "SELECT id, first_name, last_name, email, password, is_active,user_type
                FROM user 
                WHERE email = :email";
        $params = [':email' => $email];
        
        try {
            $stmt = $this->db->request($connection, $query, $params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: false;
        } catch (Exception $e) {
            error_log("Error getting user by email: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

    public function updateUser($id, $data) {
        $connection = $this->db->connexion();
        $query = "UPDATE user 
                SET first_name = :first_name,
                    last_name = :last_name,
                    email = :email
                WHERE id = :id";
        
        $params = [
            ':id' => $id,
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email']
        ];

        try {
            $stmt = $this->db->request($connection, $query, $params);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

    public function updatePassword($id, $newPassword) {
        $connection = $this->db->connexion();
        $query = "UPDATE user 
                SET password = :password 
                WHERE id = :id";
        
        $params = [
            ':id' => $id,
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ];

        try {
            $stmt = $this->db->request($connection, $query, $params);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

    public function updateProfilePicture($id, $imageData) {
        $connection = $this->db->connexion();
        $query = "UPDATE user 
                    SET profile_picture = :profile_picture 
                    WHERE id = :id";
        
        $params = [
            ':id' => $id,
            ':profile_picture' => $imageData
        ];

        try {
            $stmt = $this->db->request($connection, $query, $params);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error updating profile picture: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

    public function verifyPassword($id, $password) {
        $connection = $this->db->connexion();
        $query = "SELECT password FROM user WHERE id = :id";
        $params = [':id' => $id];

        try {
            $stmt = $this->db->request($connection, $query, $params);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                return password_verify($password, $user['password']);
            }
            return false;
        } catch (Exception $e) {
            error_log("Error verifying password: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }

public function getProfilePicture($userId) {
        $c = $this->db->connexion();
        $query = "SELECT profile_picture FROM user WHERE id = :user_id";
        $params = [':user_id' => $userId];
        $result = $this->db->request($c, $query, $params);

        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return $row['profile_picture']; // Return the image URL or path
        }
        return null; // Return null if no image is found
    }
//--------------------------------------------------------------------------------------
//users for admin gestion 
public function getUsers() {
    $c = $this->db->connexion();

    // SQL query to join user, member, card, and cardtype tables
    $sql = "
        SELECT 
            u.id AS user_id,
            u.first_name,
            u.last_name,
            u.email,
            u.profile_picture,
            u.membership_status,
            u.user_type,
            m.id AS membership_id,
            c.card_number,
            c.status AS card_status,
            c.expiration_date,
            m.membership_date,
            ct.name AS card_type
        FROM 
            user u
        LEFT JOIN 
            member m ON u.id = m.user_id
        LEFT JOIN 
            card c ON m.card_id = c.id
        LEFT JOIN 
            cardtype ct ON c.card_type_id = ct.id
        WHERE 
            u.user_type IN (:user_type1, :user_type2)
        ORDER BY 
            u.id DESC
    ";

    // Execute the query
    $stmt = $this->db->request($c, $sql, [
        ':user_type1' => 'user',
        ':user_type2' => 'member'
    ]);

    // Fetch the results
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $this->db->deconnexion();
    return $users ?: []; // Return the results or an empty array if no data is found
}

}
?>