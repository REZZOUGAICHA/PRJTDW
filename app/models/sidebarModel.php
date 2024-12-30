<?php

require_once __DIR__ . '/../helpers/Database.php';

class SidebarModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getSidebarItems() {
        $conn = $this->db->connexion();
        $sql = "SELECT name, link FROM sidebar ORDER BY order_index ASC";
        $stmt = $this->db->request($conn, $sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $results;
    }
}
?>
