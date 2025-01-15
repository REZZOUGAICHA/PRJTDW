<?php
require_once __DIR__ . '/../helpers/Database.php';
class AdminConnModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAdminByName($name) {
        $connection = $this->db->connexion();
        
        $query = "SELECT a.*, at.name as admin_type_name 
                    FROM admin a 
                    JOIN admintype at ON a.admin_type = at.id 
                    WHERE a.name = :name";
            
        try {
            $stmt = $this->db->request($connection, $query, [':name' => $name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting admin by name: " . $e->getMessage());
            return false;
        } finally {
            $this->db->deconnexion();
        }
    }
}
?>
