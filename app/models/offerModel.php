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
}