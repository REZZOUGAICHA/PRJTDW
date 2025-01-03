<?php
require_once __DIR__ . '/../models/submenuModel.php';

class SubmenuController {
    private $submenuModel;
    
    public function __construct() {
        $this->submenuModel = new SubmenuModel();
    }
    
    public function getSubmenu($pageIdentifier) {
        return $this->submenuModel->getSubmenuItems($pageIdentifier);
    }

    public function isCurrentPage($link) {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $currentPath === $link;
    }

    public function showSubmenu($pageIdentifier) {
        require_once __DIR__ . '/../Views/userView/submenuView.php';
        $view = new SubmenuView();
        // Pass the identifier directly, don't get the items here
        $view->displaySubmenu($pageIdentifier);
    }
}

?>
