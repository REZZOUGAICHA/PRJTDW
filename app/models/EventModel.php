<?php
require_once __DIR__ . '/../helpers/Database.php';

class EventModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getLatestEventsByType($type, $limit = 3) {
        $c = $this->db->connexion();
        $sql = "SELECT * FROM event WHERE type = :type ORDER BY event_date ASC LIMIT :limit";
        $stmt = $this->db->request(
            $c, 
            $sql, 
            [
                'type' => $type,
                'limit' => $limit
            ],
            [
                'limit' => PDO::PARAM_INT // Explicitly set LIMIT as integer
            ]
        );
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $events;
    }
}
?>