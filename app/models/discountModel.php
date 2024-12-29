<?php
require_once __DIR__ . '/../helpers/Database.php';class DiscountModel {
   private $db;

   public function __construct() {
       $this->db = new Database();
   }

   public function getDiscounts() {
       $c = $this->db->connexion();
       $sql = "SELECT d.name as discount_name, 
                      p.name as partner_name,
                      cp.name as category_name, 
                    ct.name as card_name,
                      d.percentage,
                      p.city,
                      d.link,
                      d.discount_type


               FROM discount d
               JOIN cardtype ct ON d.card_type_id = ct.id
               JOIN partner p ON d.partner_id = p.id 
               JOIN PartnerCategory cp ON p.category_id = cp.id";
               
       $stmt = $this->db->request($c, $sql);
       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
       $this->db->deconnexion();
       return $result;
   }
}