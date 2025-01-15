<?php
require_once __DIR__ . '/../../controllers/userController.php';
require_once __DIR__ . '/../../views/userView/TableView.php';

class UserView {
    private $userController;

    public function __construct() {
        $this->userController = new UserController(); 
    }
public function displayUsers() {
        $users = $this->userController->getAllUsers()['users'];
        
        // Transform data
        $flattenedUsers = [];
        foreach ($users as $user) {
            $flattenedUsers[] = [
                'id' => $user['id'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'card_type' => $user['card_type'] ?? 'Aucune carte',
                'user_type' => $user['user_type'],
                'status' => $user['is_active'] ? 'Actif' : 'Inactif'
            ];
        }

        // Define columns
        $columns = [
            ['label' => 'Nom complet', 'field' => 'name'],
            ['label' => 'Type de carte', 'field' => 'card_type'],
            ['label' => "Type d'utilisateur", 'field' => 'user_type'],
            ['label' => 'Statut', 'field' => 'status'],
        ];

        // Define actions
        $actions = [
            function($row) {
                return sprintf(
                    '<a href="%s/admin/users?id=%s" class="text-blue-600 hover:text-blue-800 hover:underline">Voir plus</a>',
                    BASE_URL,
                    htmlspecialchars($row['id'])
                );
            }
        ];

        echo '<div class="users-table-container">';
        $tableView = new TableView();
        $tableView->displayTable($flattenedUsers, $columns, $actions);
        echo '</div>';
    }

}
?>
