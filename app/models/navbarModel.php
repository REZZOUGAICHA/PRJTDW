<?php

require_once DIR . '/../helpers/Database.php';

class MenuModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getMainMenuItems() {
        $c = $this->db->connexion();

        
        $sql = "SELECT * FROM menu_main ORDER BY id";
        $stmt = $this->db->request($c, $sql); 
        $mainItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->db->deconnexion();

        $menu = [];
        foreach ($mainItems as $mainItem) {
            $menu[$mainItem['id']] = $mainItem;
            $menu[$mainItem['id']]['sub_items'] = [];
        }

        return $menu;
    }

    public function getSubMenuItems() {
        $c = $this->db->connexion();

        // Use the request method to execute the query
        $sql = "SELECT * FROM menu_sub ORDER BY main_id";
        $stmt = $this->db->request($c, $sql); // Call request from Database class
        $subItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->db->deconnexion();

        return $subItems;
    }

    public function getMenuItems() {
        $menu = $this->getMainMenuItems();
        $subItems = $this->getSubMenuItems();

        foreach ($subItems as $subItem) {
            if (isset($menu[$subItem['main_id']])) {
                $menu[$subItem['main_id']]['sub_items'][] = $subItem;
            }
        }

        return $menu;
    }
}
?>
