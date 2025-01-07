<?php
require_once __DIR__ . '/../helpers/Database.php';
require_once __DIR__ . '/../helpers/LinkGenerator.php';

class partnerModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // -------------------------------------------------------------------------------------------
    public function getPartnersByCategory() {
        // Establish database connection
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

            // Organizing partners into categories
            $categories[$row['category_id']]['name'] = $row['category_name'];
            $categories[$row['category_id']]['partners'][] = $partner;
        }

        return $categories;
    }

    // -------------------------------------------------------------------------------------------
    private function generatePartnerLink($id, $name) {
        return LinkGenerator::generateEntityLink('partner', $id, $name);
    }

    // -------------------------------------------------------------------------------------------
    public function createPartner($user_id, $name, $city, $description, $logo_url, $category_id) {
        $c = $this->db->connexion();

        // First, insert the partner
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
        
        // Generate the detail view link
        $link = "/admin/partner/" . $partnerId;
        
        // Update the link for the newly created partner
        $updateSql = "UPDATE Partner SET link = :link WHERE id = :id";
        $this->db->request($c, $updateSql, [
            'link' => $link,
            'id' => $partnerId
        ]);

        $this->db->deconnexion();

        return $partnerId;
    }

    // -------------------------------------------------------------------------------------------
    public function updatePartner($id, $name, $city, $description, $logo_url, $category_id, $link) {
        $c = $this->db->connexion();

        

        $sql = "UPDATE partner SET 
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
    public function deletePartner($id) {
        $c = $this->db->connexion();
        $sql = "DELETE FROM Partner WHERE id = :id";
        $this->db->request($c, $sql, ['id' => $id]);
        $this->db->deconnexion();
    }

    public function getCategories() {
        $c = $this->db->connexion();
        $query = "SELECT * FROM partnercategory ORDER BY name";
        $stmt = $this->db->request($c, $query);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $categories;
    }

    public function getCategoryById($category_id) {
        $c = $this->db->connexion();
        $query = "SELECT * FROM partnercategory WHERE id = :id";
        $stmt = $this->db->request($c, $query, ['id' => $category_id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->db->deconnexion();
        return $category;
    }

    // -------------------------------------------------------------------------------------------
    public function getPartnerById($id) {
        // Establish connection to the database
        $c = $this->db->connexion();

        // Prepare and execute the query to get the partner by ID
        $sql = "SELECT * FROM Partner WHERE id = :id";
        $stmt = $this->db->request($c, $sql, ['id' => $id]);

        // Fetch and return the partner's data
        $partner = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the database connection
        $this->db->deconnexion();

        return $partner;
    }
}
?>
