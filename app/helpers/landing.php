
<?php

require_once 'navbarController.php';
require_once 'diapoController.php';

$controller = new MenuController();
$controller->getMenu();

//$controller = new DiaporamaController();
//$controller->displayDiaporama();
?>