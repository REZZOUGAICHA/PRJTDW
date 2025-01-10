<?php
require_once __DIR__ . '/../helpers/Database.php';

class AidModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all aid types
    public function getAllAidTypes() {
        $c = $this->db->connexion();
        $sql = "SELECT * FROM AidType ORDER BY name ASC";
        $stmt = $this->db->request($c, $sql);
        $aidTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $aidTypes;
    }

    // Get file types for a specific aid type
    public function getFileTypesForAidType($aidTypeId) {
        $c = $this->db->connexion();
        $sql = "
            SELECT ft.id, ft.name, ft.description
            FROM FileType ft
            INNER JOIN AidTypeFileType atf ON atf.file_type_id = ft.id
            WHERE atf.aid_type_id = :aid_type_id
            ORDER BY ft.name ASC
        ";
        $stmt = $this->db->request($c, $sql, ['aid_type_id' => $aidTypeId]);
        $fileTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $fileTypes;
    }

    // Save the aid request and related files
    public function createAidRequest($userId, $aidTypeId, $fileData) {
        $c = $this->db->connexion();
        try {
            // Insert into the aid request table 
            $sql = "INSERT INTO AidRequest (user_id, aid_type_id) VALUES (:user_id, :aid_type_id)";
            $this->db->request($c, $sql, ['user_id' => $userId, 'aid_type_id' => $aidTypeId]);

            // Get the last inserted aid request ID
            $aidRequestId = $c->lastInsertId();

            // Insert the files associated with this request
            foreach ($fileData as $file) {
                $sql = "INSERT INTO AidFile (aid_request_id, file_type_id, file_path) VALUES (:aid_request_id, :file_type_id, :file_path)";
                $this->db->request($c, $sql, [
                    'aid_request_id' => $aidRequestId,
                    'file_type_id' => $file['file_type_id'],
                    'file_path' => $file['file_path']
                ]);
            }

            $this->db->deconnexion();
            return true;
        } catch (Exception $e) {
            $this->db->deconnexion();
            return false;
        }
    }

    public function acceptAidRequest($requestId) {
        $query = "
            UPDATE AidRequest 
            SET status = 'approved', 
                updated_at = NOW() 
            WHERE id = :request_id
        ";
        
        $connection = $this->db->connexion();
        try {
            $this->db->request($connection, $query, ['request_id' => $requestId]);
            $this->db->deconnexion();
            return true;
        } catch (Exception $e) {
            $this->db->deconnexion();
            return false;
        }
    }

    public function refuseAidRequest($requestId) {
        $query = "
            UPDATE AidRequest 
            SET status = 'rejected', 
                updated_at = NOW() 
            WHERE id = :request_id
        ";
        
        $connection = $this->db->connexion();
        try {
            $this->db->request($connection, $query, ['request_id' => $requestId]);
            $this->db->deconnexion();
            return true;
        } catch (Exception $e) {
            $this->db->deconnexion();
            return false;
        }
    }

  public function getAidTypesWithFiles() {
    $query = "
        SELECT 
            AidType.id AS aid_type_id,
            AidType.name AS aid_type_name,
            AidType.description AS aid_type_description,
            FileType.id AS file_type_id,
            FileType.name AS file_type_name,
            FileType.description AS file_type_description
        FROM 
            AidType
        LEFT JOIN 
            AidTypeFileType ON AidType.id = AidTypeFileType.aid_type_id
        LEFT JOIN 
            FileType ON AidTypeFileType.file_type_id = FileType.id
        ORDER BY 
            AidType.id, FileType.id;
    ";

    $database = new Database();
    $connection = $database->connexion(); 

    $stmt = $database->request($connection, $query);

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!isset($result[$row['aid_type_id']])) {
            $result[$row['aid_type_id']] = [
                'id' => $row['aid_type_id'],
                'name' => $row['aid_type_name'],
                'description' => $row['aid_type_description'],
                'files' => []
            ];
        }
        
        // Only add file types if they exist
        if ($row['file_type_id']) {
            $result[$row['aid_type_id']]['files'][] = [
                'id' => $row['file_type_id'],
                'name' => $row['file_type_name'],
                'description' => $row['file_type_description']
            ];
        }
    }

    return array_values($result); // Convert the associative array to a numeric array
}
 public function getAidRequestById($aidRequestId) {  // Added parameter here
        $query = "
            SELECT 
                AidRequest.id AS aid_request_id,
                AidRequest.user_id AS user_id,
                AidRequest.aid_type_id AS aid_type_id,
                AidRequest.created_at AS created_at,
                AidType.name AS aid_type_name,
                User.first_name AS first_name,
                User.last_name AS last_name
            FROM 
                AidRequest
            JOIN 
                AidType ON AidRequest.aid_type_id = AidType.id
            JOIN 
                User ON AidRequest.user_id = User.id
            WHERE 
                AidRequest.id = :aid_request_id;
        ";

        $database = new Database();
        $connection = $database->connexion(); 

        $stmt = $database->request($connection, $query, ['aid_request_id' => $aidRequestId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return [
            'id' => $row['aid_request_id'],
            'user_id' => $row['user_id'],
            'aid_type_id' => $row['aid_type_id'],
            'created_at' => $row['created_at'],
            'aid_type_name' => $row['aid_type_name'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']
        ];
    }


public function getAidRequests(){
    $query = "
        SELECT 
            AidRequest.id AS aid_request_id,
            AidRequest.user_id AS user_id,
            AidRequest.aid_type_id AS aid_type_id,
            AidRequest.created_at AS created_at,
            AidType.name AS aid_type_name,
            User.first_name AS first_name,
            User.last_name AS last_name
        FROM 
            AidRequest
        JOIN 
            AidType ON AidRequest.aid_type_id = AidType.id
        JOIN 
            User ON AidRequest.user_id = User.id
        ORDER BY 
            AidRequest.created_at DESC;
    ";

    $database = new Database();
    $connection = $database->connexion(); 

    $stmt = $database->request($connection, $query);

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = [
            'id' => $row['aid_request_id'],
            'user_id' => $row['user_id'],
            'aid_type_id' => $row['aid_type_id'],
            'created_at' => $row['created_at'],
            'aid_type_name' => $row['aid_type_name'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']
        ];
    }

    return $result;
}

}
?>