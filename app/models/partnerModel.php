<?php
require_once __DIR__ . '/../helpers/Database.php';

class partnerModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getPartnersByCategory() {
        $c = $this->db->connexion();
        $sql = "
            SELECT 
                pc.id AS category_id,
                pc.name AS category_name,
                p.id AS partner_id,
                p.name AS partner_name,
                p.city,
                p.logo_url,
                p.link,
                JSON_ARRAYAGG(
                    JSON_OBJECT('name', d.name, 'percentage', d.percentage)
                ) AS discounts
            FROM Partner p
            LEFT JOIN PartnerCategory pc ON p.category_id = pc.id
            LEFT JOIN Discount d ON p.id = d.partner_id
            GROUP BY pc.id, p.id
            ORDER BY pc.name, p.name
        ";
        $stmt = $this->db->request($c, $sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();

        // Organize results into categories
        $categories = [];
        foreach ($rows as $row) {
            $discounts = json_decode($row['discounts'], true) ?? [];
            $partner = [
                'id' => $row['partner_id'],
                'name' => $row['partner_name'],
                'city' => $row['city'],
                'logo_url' => $row['logo_url'],
                'link' => $row['link'],
                'discounts' => $discounts,
            ];
            $categories[$row['category_id']]['name'] = $row['category_name'];
            $categories[$row['category_id']]['partners'][] = $partner;
        }

        return $categories;
    }
}
?>
