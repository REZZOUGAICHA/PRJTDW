<?php
require_once __DIR__ . '/../helpers/Database.php';

class diapoModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getImages() {
        $c = $this->db->connexion(); 

        $sql = "SELECT id, lien, titre FROM diaporama ORDER BY id ASC";
        $stmt = $this->db->request($c, $sql); 
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        $this->db->deconnexion(); 

        return $images;
    }
}
?>
