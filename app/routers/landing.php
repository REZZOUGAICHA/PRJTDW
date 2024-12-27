
<?php

require_once __DIR__ . '/../controllers/navbarController.php';
require_once __DIR__ . '/../controllers/diapoController.php';
require_once __DIR__ . '/../controllers/eventController.php';

echo '<link rel="stylesheet" href="../../public/css/output.css">';
$controller = new MenuController();
$controller->getMenu();

$controller = new DiaporamaController();
$controller->displayDiaporama();

$eventController = new EventController();
$eventController->displayEvents();
?>
