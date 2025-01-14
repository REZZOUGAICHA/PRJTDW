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
            d.id AS discount_id,
            d.name AS discount_name,
            d.percentage AS discount_percentage,
            d.description AS discount_description,
            d.discount_type AS discount_discount_type,
            d.start_date AS discount_start_date,
            d.end_date AS discount_end_date,
            d.card_type_id AS discount_card_type_id,
            o.id AS offer_id,
            o.name AS offer_name,
            o.description AS offer_description,
            o.start_date AS offer_start_date,
            o.end_date AS offer_end_date,
            o.card_type_id AS offer_card_type_id
        FROM Partner p
        LEFT JOIN PartnerCategory pc ON p.category_id = pc.id
        LEFT JOIN discount d ON p.id = d.partner_id
        LEFT JOIN offer o ON p.id = o.partner_id
        ORDER BY pc.name, p.name
    ";

    $stmt = $this->db->request($c, $sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $this->db->deconnexion();

    // Group into categories and remove duplicates
    $categories = [];
    foreach ($rows as $row) {
        $partnerId = $row['partner_id'];
        $categoryId = $row['category_id'];

        // Initialize the partner data if it doesn't exist yet
        if (!isset($categories[$categoryId])) {
            $categories[$categoryId] = [
                'name' => $row['category_name'],
                'partners' => []
            ];
        }

        if (!isset($categories[$categoryId]['partners'][$partnerId])) {
            $categories[$categoryId]['partners'][$partnerId] = [
                'id' => $partnerId,
                'name' => $row['partner_name'],
                'city' => $row['city'],
                'logo_url' => $row['logo_url'],
                'link' => $row['link'],
                'discounts' => [],
                'offers' => []
            ];
        }

        // Add the discount if it exists
        if ($row['discount_id'] !== null) {
            $discount = [
                'id' => $row['discount_id'],
                'name' => $row['discount_name'],
                'percentage' => $row['discount_percentage'],
                'description' => $row['discount_description'],
                'discount_type' => $row['discount_discount_type'],
                'start_date' => $row['discount_start_date'],
                'end_date' => $row['discount_end_date'],
                'card_type_id' => $row['discount_card_type_id']
            ];

            // Ensure the discount is unique
            if (!in_array($discount, $categories[$categoryId]['partners'][$partnerId]['discounts'])) {
                $categories[$categoryId]['partners'][$partnerId]['discounts'][] = $discount;
            }
        }

        // Add the offer if it exists
        if ($row['offer_id'] !== null) {
            $offer = [
                'id' => $row['offer_id'],
                'name' => $row['offer_name'],
                'description' => $row['offer_description'],
                'start_date' => $row['offer_start_date'],
                'end_date' => $row['offer_end_date'],
                'card_type_id' => $row['offer_card_type_id']
            ];

            // Ensure the offer is unique
            if (!in_array($offer, $categories[$categoryId]['partners'][$partnerId]['offers'])) {
                $categories[$categoryId]['partners'][$partnerId]['offers'][] = $offer;
            }
        }
    }

    return $categories;
}



    // Update offer
public function updatePartnerOffer($offerId, $partnerId, $cardTypeName, $name, $description, $startDate, $endDate) {
    $conn = $this->db->connexion();

    // Fetch the card_type_id using the card type name
    $cardTypeSql = "SELECT id FROM cardtype WHERE name = :cardTypeName";
    $cardTypeParams = [':cardTypeName' => $cardTypeName];
    $cardTypeStmt = $this->db->request($conn, $cardTypeSql, $cardTypeParams);
    $cardTypeResult = $cardTypeStmt->fetch(PDO::FETCH_ASSOC);

    if (!$cardTypeResult) {
        $this->db->deconnexion();
        throw new Exception("Card type '$cardTypeName' does not exist");
    }

    $cardTypeId = $cardTypeResult['id'];

    // Update the offer
    $sql = "UPDATE offer 
            SET card_type_id = :cardTypeId, name = :name, description = :description, 
                start_date = :startDate, end_date = :endDate
            WHERE id = :offerId AND partner_id = :partnerId";
    $params = [
        ':cardTypeId' => $cardTypeId,
        ':name' => $name,
        ':description' => $description,
        ':startDate' => $startDate,
        ':endDate' => $endDate,
        ':offerId' => $offerId,
        ':partnerId' => $partnerId
    ];

    $result = $this->db->request($conn, $sql, $params);
    $this->db->deconnexion();
    return $result;
}

// Add new offer
public function addPartnerOffer($partnerId, $cardTypeName, $name, $description, $startDate, $endDate) {
    $conn = $this->db->connexion();

    // Fetch the card_type_id using the card type name
    $cardTypeSql = "SELECT id FROM cardtype WHERE name = :cardTypeName";
    $cardTypeParams = [':cardTypeName' => $cardTypeName];
    $cardTypeStmt = $this->db->request($conn, $cardTypeSql, $cardTypeParams);
    $cardTypeResult = $cardTypeStmt->fetch(PDO::FETCH_ASSOC);

    if (!$cardTypeResult) {
        $this->db->deconnexion();
        throw new Exception("Card type '$cardTypeName' does not exist");
    }

    $cardTypeId = $cardTypeResult['id'];

    // Insert new offer
    $sql = "INSERT INTO offer (partner_id, card_type_id, name, description, start_date, end_date) 
            VALUES (:partnerId, :cardTypeId, :name, :description, :startDate, :endDate)";
    $params = [
        ':partnerId' => $partnerId,
        ':cardTypeId' => $cardTypeId,
        ':name' => $name,
        ':description' => $description,
        ':startDate' => $startDate,
        ':endDate' => $endDate
    ];

    $result = $this->db->request($conn, $sql, $params);
    $this->db->deconnexion();
    return $result;
}


    // -------------------------------------------------------------------------------------------
 public function createPartnerUser($first_name, $last_name, $email, $password, $logo_url) {
    $c = $this->db->connexion();
    
    $sql = "INSERT INTO user (first_name, last_name, email, password, user_type, profile_picture) 
            VALUES (:first_name, :last_name, :email, :password, 'partner', :profile_picture)";
    
    $this->db->request($c, $sql, [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'profile_picture' => $logo_url
    ]);
    
    $userId = $c->lastInsertId();
    $this->db->deconnexion();
    return $userId;
}

public function createPartner($user_id, $name, $city, $description, $logo_url, $category_id) {
    $c = $this->db->connexion();

    // First update the user's profile picture
    $updateUserSql = "UPDATE user SET profile_picture = :logo_url WHERE id = :user_id";
    $this->db->request($c, $updateUserSql, [
        'logo_url' => $logo_url,
        'user_id' => $user_id
    ]);

    // Then create the partner entry
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

    public function deletePartner($id) {
        $c = $this->db->connexion();
        $sql = "DELETE FROM Partner WHERE id = :id";
        $this->db->request($c, $sql, ['id' => $id]);
        $this->db->deconnexion();
    }
    // -------------------------------------------------------------------------------------------
 public function addPartnerDiscount($partnerId, $cardTypeName, $name, $description, $percentage, $discountType, $startDate, $endDate) {
    $conn = $this->db->connexion();

    // Fetch the card_type_id using the card type name
    $cardTypeSql = "SELECT id FROM cardtype WHERE name = :cardTypeName";
    $cardTypeParams = [':cardTypeName' => $cardTypeName];
    $cardTypeStmt = $this->db->request($conn, $cardTypeSql, $cardTypeParams);
    $cardTypeResult = $cardTypeStmt->fetch(PDO::FETCH_ASSOC);

    if (!$cardTypeResult) {
        $this->db->deconnexion();
        throw new Exception("Card type '$cardTypeName' does not exist");
    }

    $cardTypeId = $cardTypeResult['id'];

    // Insert the discount
    $sql = "INSERT INTO discount (partner_id, card_type_id, name, description, percentage, discount_type, start_date, end_date)
            VALUES (:partnerId, :cardTypeId, :name, :description, :percentage, :discountType, :startDate, :endDate)";
    $params = [
        ':partnerId' => $partnerId,
        ':cardTypeId' => $cardTypeId,
        ':name' => $name,
        ':description' => $description,
        ':percentage' => $percentage,
        ':discountType' => $discountType,
        ':startDate' => $startDate,
        ':endDate' => $endDate
    ];
    $result = $this->db->request($conn, $sql, $params);
    $this->db->deconnexion();
    return $result;
}


    public function updatePartnerDiscount($discountId, $partnerId, $cardTypeName, $name, $description, $percentage, $discountType, $startDate, $endDate) {
    $conn = $this->db->connexion();

    // Fetch the card_type_id using the card type name
    $cardTypeSql = "SELECT id FROM cardtype WHERE name = :cardTypeName";
    $cardTypeParams = [':cardTypeName' => $cardTypeName];
    $cardTypeStmt = $this->db->request($conn, $cardTypeSql, $cardTypeParams);
    $cardTypeResult = $cardTypeStmt->fetch(PDO::FETCH_ASSOC);

    if (!$cardTypeResult) {
        $this->db->deconnexion();
        throw new Exception("Card type '$cardTypeName' does not exist");
    }

    $cardTypeId = $cardTypeResult['id'];

    // Update the discount
    $sql = "UPDATE discount 
            SET card_type_id = :cardTypeId, name = :name, description = :description, 
                percentage = :percentage, discount_type = :discountType, 
                start_date = :startDate, end_date = :endDate
            WHERE id = :discountId AND partner_id = :partnerId";
    $params = [
        ':cardTypeId' => $cardTypeId,
        ':name' => $name,
        ':description' => $description,
        ':percentage' => $percentage,
        ':discountType' => $discountType,
        ':startDate' => $startDate,
        ':endDate' => $endDate,
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

    public function getCardTypes() {
        $conn = $this->db->connexion();
        $sql = "SELECT id, name FROM cardtype";
        $stmt = $this->db->request($conn, $sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    //for login partner 
   public function getPartnerByUserId($userId) {
    $c = $this->db->connexion();
    $sql = "SELECT * FROM Partner WHERE user_id = :user_id";
    $partner = $this->db->request($c, $sql, ['user_id' => $userId]);
    $this->db->deconnexion();
    return $partner ? $partner[0] : null;
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
