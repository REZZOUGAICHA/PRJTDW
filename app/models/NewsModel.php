<?php
require_once __DIR__ . '/../helpers/Database.php';

class AnnounceModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getLatestAnnounces($limit = 3) {
        $c = $this->db->connexion();
        $sql = "SELECT * FROM annonces ORDER BY id DESC LIMIT :limit";
        $stmt = $this->db->request(
            $c, 
            $sql, 
            [
                'limit' => $limit
            ],
            [
                'limit' => PDO::PARAM_INT
            ]
        );
        $announces = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $announces;
    }

    public function getannounceById($id) {
        $c = $this->db->connexion();
        $sql = "SELECT * FROM annonces WHERE id = :id";
        $stmt = $this->db->request(
            $c, 
            $sql, 
            [
                'id' => $id
            ]
        );
        $announce = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $announce;
    }

    public function createAnnounce($name, $description, $picture_url) {
        $c = $this->db->connexion();
        $sql = "INSERT INTO annonces (name, description, picture_url) VALUES (:name, :description, :picture_url)";
        $stmt = $this->db->request(
            $c, 
            $sql, 
            [
                'name' => $name,
                'description' => $description,
                'picture_url' => $picture_url
            ]
        );
        $this->db->deconnexion();
        return $stmt->rowCount() > 0;
    }

    public function updateAnnounce($id, $name, $description, $picture_url) {
        $c = $this->db->connexion();
        $sql = "UPDATE annonces SET name = :name, description = :description, picture_url = :picture_url WHERE id = :id";
        $stmt = $this->db->request(
            $c, 
            $sql, 
            [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'picture_url' => $picture_url
            ]
        );
        $this->db->deconnexion();
        return $stmt->rowCount() > 0;
    }

    public function deleteAnnounce($id) {
        $c = $this->db->connexion();
        $sql = "DELETE FROM annonces WHERE id = :id";
        $stmt = $this->db->request(
            $c, 
            $sql, 
            [
                'id' => $id
            ]
        );
        $this->db->deconnexion();
        return $stmt->rowCount() > 0;
    }
    public function getAllAnnounces() {
        $c = $this->db->connexion();
        $sql = "SELECT * FROM annonces ORDER BY id DESC";
        $stmt = $this->db->request($c, $sql);
        $announces = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $announces;
    }
}
?>
