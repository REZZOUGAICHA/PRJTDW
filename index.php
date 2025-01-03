<?php
define('BASE_URL', '/TDW'); // Base URL of your application

// Set JSON header for AJAX requests
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
}

// Include the routers
require_once __DIR__ . '/app/routers/Router.php';
require_once __DIR__ . '/app/routers/AdminRouter.php';

// Get the requested page (default to 'acceuil')
$page = isset($_GET['page']) ? $_GET['page'] : 'acceuil';

// Check if the route is for admin or user
if (strpos($page, 'admin/') === 0) {
    // Remove 'admin/' prefix and pass the request to the AdminRouter
    $adminPage = str_replace('admin/', '', $page);
    AdminRouter::route($adminPage);
} else {
    // Pass the request to the user Router
    Router::route($page);
}
