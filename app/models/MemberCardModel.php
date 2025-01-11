<?php
require_once __DIR__ . '/../helpers/Database.php';

class MemberCardModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connexion(); // Establish a connection using the Database class
    }

    public function getMemberCard($userId) {
    $sql = "SELECT 
                m.id AS member_id,
                u.first_name,
                u.last_name,
                c.card_number,
                c.issue_date,
                c.expiration_date,
                ct.name AS card_type,
                t.asso_name,
                t.logo_link
            FROM user u
            JOIN member m ON u.id = m.user_id
            JOIN card c ON m.card_id = c.id
            JOIN cardtype ct ON c.card_type_id = ct.id
            JOIN topbar t ON 1 = 1
            WHERE u.id = :userId AND u.user_type = 'member'";

    $database = new Database();
    $stmt = $database->request(
        $this->db,
        $sql,
        [':userId' => $userId],
        [':userId' => PDO::PARAM_INT]
    );

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
?>
