<?php
define('BASE_URL', '/TDW'); // This is the base URL of your application

// Set JSON header for AJAX requests
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
}

// Include the router
require_once __DIR__ . '/app/routers/Router.php';

// Get the requested page (default to 'home')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Pass the request to the router
Router::route($page);
