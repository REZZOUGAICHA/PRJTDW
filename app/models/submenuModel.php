<?php
class SubmenuModel {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connexion();
    }
    
    public function getSubmenuItems($pageIdentifier) {
        $sql = "SELECT s.id, s.name, si.name as item_name, si.link, si.order_position 
                FROM submenus s 
                JOIN submenu_items si ON s.id = si.submenu_id 
                WHERE s.page_identifier = :pageIdentifier AND s.active = 1 AND si.active = 1 
                ORDER BY si.order_position ASC";
        
        $params = [
            ':pageIdentifier' => $pageIdentifier
        ];
        
        $types = [
            ':pageIdentifier' => PDO::PARAM_STR
        ];
        
        $result = $this->db->request($this->conn, $sql, $params, $types);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function __destruct() {
        $this->db->deconnexion();
    }
}
?>