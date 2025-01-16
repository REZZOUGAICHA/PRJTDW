<?php

require_once __DIR__ . '/../../views/userView/TableView.php';


class DonView {
    public function displayDons($dons) {
        echo '<div class="mb-6">';
        echo '<h1 class="text-2xl font-bold text-gray-800 mb-4">Liste des Dons</h1>';
        echo '</div>';

        // Définir les colonnes
        $columns = [
            ['label' => 'Nom de l\'Utilisateur', 'field' => 'user'],
            ['label' => 'Montant', 'field' => 'amount'],
            ['label' => 'Date de Paiement', 'field' => 'payment_date'],
            ['label' => 'Description', 'field' => 'description'],
            ['label' => 'Statut', 'field' => 'status']
        ];

        // Définir les actions
        $actions = [
            function($row) {
                return sprintf(
                    '<a href="%s/admin/dons?action=view&id=%s" class="text-blue-600 hover:text-blue-800 hover:underline">Voir plus</a>',
                    BASE_URL,
                    htmlspecialchars($row['id'])
                );
            }
        ];

        // Afficher le tableau
        echo '<div class="don-table-container">';
        $tableView = new TableView();
        $tableView->displayTable($dons, $columns, $actions);
        echo '</div>';

        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
        echo '<script src="' . BASE_URL . '/public/js/filterTable.js"></script>';
        echo '<script>
            $(document).ready(function() {
                initializeTableFilters(".don-table-container", [
                    { label: "Montant", columnIndex: 2 },
                    { label: "Statut", columnIndex: 5 }
                ]);
            });
        </script>';
    }
}
