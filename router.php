<?php
class Router {
    private $routes = [];

    public function addRoute($pattern, $handler) {
        $this->routes[$pattern] = $handler;
    }

    public function handleRequest($uri) {
        // Remove query string if present
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Check for entity view pattern
        if (preg_match('/^\/entity\/([^\/]+)\/(\d+)-/', $uri, $matches)) {
            $entityType = $matches[1];
            $id = $matches[2];
            
            // Map entity types to their respective table names
            $tableMap = [
                'partner' => 'partners',
                'event' => 'events'
                // Add other entity types as needed
            ];
            
            if (isset($tableMap[$entityType])) {
                $entityViewController = new EntityViewController();
                return $entityViewController->displayEntity($uri, $tableMap[$entityType]);
            }
        }
        
        // Handle 404
        header("HTTP/1.0 404 Not Found");
        echo "Page not found";
    }
}
?>