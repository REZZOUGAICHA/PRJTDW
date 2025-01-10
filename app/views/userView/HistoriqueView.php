<?php
require_once __DIR__ . '/../../views/userView/TableView.php';

class HistoriqueView {
    private $statusMapping = [
        'pending' => 'En attente',
        'approved' => 'Approuvé',
        'rejected' => 'Rejeté'
    ];

    public function displayDonHistory($donHistory) {
        if (!is_array($donHistory)) {
            echo '<div class="alert alert-error">Une erreur est survenue lors de la récupération de l\'historique.</div>';
            return;
        }

        echo '<div class="mb-6">';
        echo '<h1 class="text-2xl font-bold text-gray-800 mb-4">Historique des Dons</h1>';
        echo '</div>';

        // Transform data
        $flattenedDonHistory = array_map(function($don) {
            return [
                'id' => $don['id'],
                'amount' => number_format($don['amount'], 2, ',', ' ') . ' DA',
                'payment_date' => (new DateTime($don['payment_date']))->format('d/m/Y'),
                'status' => $this->getStatusLabel($don['status'] ?? 'pending'),
                'receipt' => !empty($don['file_path']) ? 
                    '<a href="' . htmlspecialchars($don['file_path']) . '" class="text-blue-600 hover:text-blue-800">Voir le reçu</a>' : 
                    'Non disponible'
            ];
        }, $donHistory);

        // Define columns
        $columns = [
            
            ['label' => 'Montant', 'field' => 'amount'],
            ['label' => 'Date du Paiement', 'field' => 'payment_date'],
            ['label' => 'Statut', 'field' => 'status'],
            
        ];

        // Render the table
        echo '<div class="don-history-table-container">';
        $tableView = new TableView();
        $tableView->displayTable($flattenedDonHistory, $columns);
        echo '</div>';
    }

    private function getStatusLabel($status) {
        return $this->statusMapping[$status] ?? 'Inconnu';
    }

}
?>
