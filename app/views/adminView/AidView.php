<?php
require_once __DIR__ . '/../../views/userView/TableView.php';

class AidView {
    public function displayAidRequests($aidRequests) {
        echo '<div class="mb-6">';
        echo '<h1 class="text-2xl font-bold text-gray-800 mb-4">Demandes d\'Aide</h1>';
        echo '</div>';

        // Transform data
        $flattenedAidRequests = [];
        foreach ($aidRequests as $aidRequest) {
            $flattenedAidRequests[] = [
                'id' => $aidRequest['id'],
                'user_name' => $aidRequest['first_name'] . ' ' . $aidRequest['last_name'],
                'aid_type_name' => $aidRequest['aid_type_name'],
                'status' => isset($aidRequest['status']) ? $aidRequest['status'] : 'En attente',
                'request_date' => (new DateTime($aidRequest['created_at']))->format('d/m/Y H:i')
            ];
        }

        // Define columns
        $columns = [
            ['label' => 'ID', 'field' => 'id'],
            ['label' => 'Nom de l\'Utilisateur', 'field' => 'user_name'],
            ['label' => 'Type d\'Aide', 'field' => 'aid_type_name'],
            ['label' => 'Statut', 'field' => 'status'],
            ['label' => 'Date de Demande', 'field' => 'request_date']
        ];

        // Define actions
        $actions = [
            function($row) {
                return sprintf(
                    '<a href="%s/admin/aide/demande?id=%s" class="text-blue-600 hover:text-blue-800 hover:underline">Voir plus</a>',
                    BASE_URL,
                    htmlspecialchars($row['id'])
                );
            }
        ];

        echo '<div class="aid-request-table-container">';
        $tableView = new TableView();
        $tableView->displayTable($flattenedAidRequests, $columns, $actions);
        echo '</div>';
    }
}
?>