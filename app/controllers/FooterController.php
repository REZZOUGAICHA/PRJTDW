<?php
require_once __DIR__ . '/../Views/userView/footerView.php';
class FooterController {
    public function showFooter() {
        $view = new FooterView();
        $view->displayFooterMenu();
    }
}
?>
