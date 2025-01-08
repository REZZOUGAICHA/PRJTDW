<?php
require_once __DIR__ . '/../helpers/Database.php';
class EventModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // limiting to  3 events
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
                'limit' => PDO::PARAM_INT
            ]
        );
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $events;
    }

    // Get all events by type
    public function getAllEventsByType($type) {
        $c = $this->db->connexion();
        $sql = "SELECT * FROM event WHERE type = :type ORDER BY event_date ASC";
        $stmt = $this->db->request($c, $sql, ['type' => $type]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $events;
    }
}
