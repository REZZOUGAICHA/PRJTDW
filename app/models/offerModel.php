<?php
require_once __DIR__ . '/../helpers/Database.php';
class offerModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getoffers() {
        $c = $this->db->connexion();
        $sql = "SELECT o.name as offer_name, 
                    o.description as offer_description,
                    p.name as partner_name,
                    cp.name as category_name, 
                    ct.name as card_name,
                    o.start_date, 
                    o.end_date,
                    p.city


                FROM offer o
                JOIN cardtype ct ON o.card_type_id = ct.id
                JOIN partner p ON o.partner_id = p.id 
                JOIN PartnerCategory cp ON p.category_id = cp.id";

        $stmt = $this->db->request($c, $sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $result;
    }

public function getOffersByType() {
        $c = $this->db->connexion();
        $sql = "
            SELECT 
                o.id AS offer_id,
                o.name AS offer_name,
                o.description,
                o.offer_type,
                DATE(o.start_date) AS start_date,
                DATE(o.end_date) AS end_date,
                p.name AS partner_name,
                ct.name AS card_type_name,
                p.link AS partner_link
            FROM Offer o
            LEFT JOIN Partner p ON o.partner_id = p.id
            LEFT JOIN CardType ct ON o.card_type_id = ct.id
            ORDER BY o.offer_type, o.name
        ";
        $stmt = $this->db->request($c, $sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();

        // Organize offers into groups by type
        $groupedOffers = [];
        foreach ($rows as $row) {
            $groupedOffers[$row['offer_type']][] = [
                'id' => $row['offer_id'],
                'name' => $row['offer_name'],
                'description' => $row['description'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
                'partner_name' => $row['partner_name'],
                'card_type_name' => $row['card_type_name'],
                'partner_link' => $row['partner_link'],
            ];
        }

        return $groupedOffers;
    }

}