
<?php
define('DIR', __DIR__);
require_once DIR . '/../controllers/navbarController.php';
require_once DIR . '/../controllers/diapoController.php';


$controller = new MenuController();
$controller->getMenu();

$controller = new DiaporamaController();
$controller->displayDiaporama();
?>