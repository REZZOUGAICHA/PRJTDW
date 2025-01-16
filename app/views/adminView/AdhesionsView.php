<?php
require_once __DIR__ . '/../../views/userView/TableView.php';


class MembershipTableView {
    public function display($applications) {
        echo '<div class="mb-6">';
        echo '<h1 class="text-2xl font-bold text-gray-800 mb-4">Applications de Membership</h1>';
        echo '</div>';

        // Transform data
        $flattenedApplications = [];
        foreach ($applications as $application) {
            $flattenedApplications[] = [
                'id' => $application['id'],
                'user_name' => $application['first_name'] . ' ' . $application['last_name'],
                'card_name' => $application['card_name'],
                'status' => $application['status'],
                'application_date' => $application['application_date']
            ];
        }

        // Define columns
        $columns = [
            ['label' => 'Nom de l\'Utilisateur', 'field' => 'user_name'],
            ['label' => 'Type de Carte', 'field' => 'card_name'],
            ['label' => 'Statut', 'field' => 'status'],
            ['label' => 'Date de Demande', 'field' => 'application_date']
        ];

        // Define actions
        $actions = [
            function($row) {
                return sprintf(
                    '<a href="%s/admin/adhesions?action=view&id=%s" class="text-blue-600 hover:text-blue-800 hover:underline">Voir plus</a>',
                    BASE_URL,
                    htmlspecialchars($row['id'])
                );
            }
        ];

        echo '<div class="membership-table-container">';
        $tableView = new TableView();
        $tableView->displayTable($flattenedApplications, $columns, $actions);
        echo '</div>';

        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
        echo '<script src="' . BASE_URL . '/public/js/filterTable.js"></script>';
        echo '<script>
            $(document).ready(function() {
                initializeTableFilters(".membership-table-container", [
                    { label: "Type de Carte", columnIndex: 2 },
                    { label: "Statut", columnIndex: 3 },
                    { label: "application_date", columnIndex: 4 } 
                ]);
            });
        </script>';
    }
}

?>
