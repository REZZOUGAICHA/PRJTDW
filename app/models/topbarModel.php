<?php

require_once __DIR__ . '/../helpers/Database.php';

class TopbarModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getTopbarData() {
        $c = $this->db->connexion();

        $sql = "SELECT * FROM topbar LIMIT 1";
        $stmt = $this->db->request($c, $sql);
        $topbarData = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->db->deconnexion();
        return $topbarData;
    }

    public function getSocialMediaLinks() {
        $c = $this->db->connexion();

        $sql = "SELECT * FROM social_media ORDER BY id";
        $stmt = $this->db->request($c, $sql);
        $socialMediaLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->db->deconnexion();
        return $socialMediaLinks;
    }
}
?>
