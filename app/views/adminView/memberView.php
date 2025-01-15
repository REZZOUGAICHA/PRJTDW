<?php
require_once __DIR__ . '/../../controllers/userController.php';
require_once __DIR__ . '/../../views/userView/TableView.php';

class MemberView {
    private $userController;

    public function __construct() {
        $this->userController = new UserController(); 
    }
public function displayUsers($users) {
    if (empty($users)) {
        echo "<p>No users found.</p>";
        return;
    }

    // Prepare data for the table
    $flattenedUsers = [];
    foreach ($users as $user) {
        $flattenedUsers[] = [
            'user_id' => $user['user_id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'card_type' => $user['card_type'],
            'user_type' => $user['user_type'],
            'card_status' => $user['card_status'],
            'expiration_date' => $user['expiration_date'],
            'membership_date' => $user['membership_date'],
            'card_number' => $user['card_number'],
            'profile_picture' => $user['profile_picture'],
        ];
    }

    // Define table columns
    $columns = [
        ['label' => 'First Name', 'field' => 'first_name'],
        ['label' => 'Last Name', 'field' => 'last_name'],
        ['label' => 'Email', 'field' => 'email'],
        ['label' => 'Card Type', 'field' => 'card_type'],
        ['label' => 'User Type', 'field' => 'user_type'],
        ['label' => 'Card Status', 'field' => 'card_status'],
        ['label' => 'Expiration Date', 'field' => 'expiration_date'],
        ['label' => 'Membership Date', 'field' => 'membership_date'],
        ['label' => 'Card Number', 'field' => 'card_number'],
        ['label' => 'Profile Picture', 'field' => 'profile_picture'],
    ];

    // Define actions (for example, view user details)
    $actions = [
        function($row) {
            return sprintf(
                '<a href="%s/admin/users?id=%s" class="text-blue-600 hover:text-blue-800 hover:underline">View Details</a>',
                BASE_URL,
                htmlspecialchars($row['user_id'])
            );
        }
    ];

    echo '<div class="users-table-container">';
    $tableView = new TableView();
    $tableView->displayTable($flattenedUsers, $columns, $actions);
    echo '</div>';
}
}
