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
}
?>
