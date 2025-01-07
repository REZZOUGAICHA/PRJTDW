<?php 
require_once __DIR__ . '/../helpers/Database.php';
class EntityViewModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getEntityByLink($link, $tableName) {
        $c = $this->db->connexion();
        
        // Extract ID from link
        preg_match('/\/entity\/\w+\/(\d+)-/', $link, $matches);
        $id = $matches[1] ?? null;
        
        if (!$id) return null;

        // Get all columns for the table
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        $stmt = $this->db->request($c, $sql, ['id' => $id]);
        $entity = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->db->deconnexion();
        return $entity;
    }
}
?>
