<?php 
require_once __DIR__ . '/../helpers/Database.php';

class partnerModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // -------------------------------------------------------------------------------------------
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
                    JSON_OBJECT('id', d.id, 'name', d.name, 'percentage', d.percentage)
                ) AS discounts
            FROM Partner p
            LEFT JOIN PartnerCategory pc ON p.category_id = pc.id
            LEFT JOIN discount d ON p.id = d.partner_id
            GROUP BY pc.id, p.id
            ORDER BY pc.name, p.name
        ";

        $stmt = $this->db->request($c, $sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->db->deconnexion();

        // Group into categories
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

    // -------------------------------------------------------------------------------------------
    public function createPartner($user_id, $name, $city, $description, $logo_url, $category_id) {
        $c = $this->db->connexion();

        $sql = "INSERT INTO Partner (user_id, name, city, description, logo_url, category_id) 
                VALUES (:user_id, :name, :city, :description, :logo_url, :category_id)";

        $this->db->request($c, $sql, [
            'user_id' => $user_id,
            'name' => $name,
            'city' => $city,
            'description' => $description,
            'logo_url' => $logo_url,
            'category_id' => $category_id
        ]);

        $partnerId = $c->lastInsertId();

        $this->db->deconnexion();
        return $partnerId;
    }

    // -------------------------------------------------------------------------------------------
    public function updatePartner($id, $name, $city, $description, $logo_url, $category_id, $link) {
        $c = $this->db->connexion();

        $sql = "UPDATE Partner SET 
                name = :name,
                city = :city,
                description = :description,
                logo_url = :logo_url,
                category_id = :category_id,
                link = :link
                WHERE id = :id";

        $this->db->request($c, $sql, [
            'id' => $id,
            'name' => $name,
            'city' => $city,
            'description' => $description,
            'logo_url' => $logo_url,
            'category_id' => $category_id,
            'link' => $link
        ]);

        $this->db->deconnexion();
    }

    // -------------------------------------------------------------------------------------------
    
public function addPartnerDiscount($partnerId, $name, $percentage) {
        $conn = $this->db->connexion();
        $sql = "INSERT INTO discount (partner_id, name, percentage) VALUES (:partnerId, :name, :percentage)";
        $params = [
            ':partnerId' => $partnerId,
            ':name' => $name,
            ':percentage' => $percentage
        ];
        $result = $this->db->request($conn, $sql, $params);
        $this->db->deconnexion();
        return $result;
    }

    public function updatePartnerDiscount($discountId, $partnerId, $name, $percentage) {
        $conn = $this->db->connexion();
        $sql = "UPDATE discount SET name = :name, percentage = :percentage WHERE id = :discountId AND partner_id = :partnerId";
        $params = [
            ':name' => $name,
            ':percentage' => $percentage,
            ':discountId' => $discountId,
            ':partnerId' => $partnerId
        ];
        $result = $this->db->request($conn, $sql, $params);
        $this->db->deconnexion();
        return $result;
    }

    public function deletePartnerDiscount($discountId, $partnerId) {
        $conn = $this->db->connexion();
        $sql = "DELETE FROM discount WHERE id = :discountId AND partner_id = :partnerId";
        $params = [
            ':discountId' => $discountId,
            ':partnerId' => $partnerId
        ];
        $result = $this->db->request($conn, $sql, $params);
        $this->db->deconnexion();
        return $result;
    }


    // -------------------------------------------------------------------------------------------
    public function getCategories() {
        $c = $this->db->connexion();
        $query = "SELECT * FROM PartnerCategory ORDER BY name";
        $stmt = $this->db->request($c, $query);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $categories;
    }

    // -------------------------------------------------------------------------------------------
    public function getCategoryById($category_id) {
        $c = $this->db->connexion();
        $query = "SELECT * FROM PartnerCategory WHERE id = :id";
        $stmt = $this->db->request($c, $query, ['id' => $category_id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $category;
    }

    // -------------------------------------------------------------------------------------------
    public function getPartnerById($id) {
        $c = $this->db->connexion();
        $sql = "SELECT * FROM Partner WHERE id = :id";
        $stmt = $this->db->request($c, $sql, ['id' => $id]);
        $partner = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $partner;
    }

    public function getPartners() {
        $c = $this->db->connexion();
        $sql = "SELECT * FROM Partner";
        $stmt = $this->db->request($c, $sql);
        $partners = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $partners;
    }
}
?>
