<?php
require_once 'app/controllers/EntityViewController.php';
require_once 'app/views/adminView/EntityView.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/TDW', '', $uri);
$params = explode('/', trim($uri, '/'));

if ($params[0] === 'entity' && isset($params[1]) && isset($params[2])) {
    $entityType = $params[1];
    $tableMap = [
        'partner' => 'Partner',
        'event' => 'Event'
    ];
    
    if (isset($tableMap[$entityType])) {
        $view = new EntityView();
        $view->displayEntity($uri, $tableMap[$entityType]);
        exit;
    }
}

// Handle 404
header("HTTP/1.0 404 Not Found");
echo "Page not found";
?>