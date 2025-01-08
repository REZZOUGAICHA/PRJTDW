<?php
require_once __DIR__ . '/../../controllers/DonController.php';
require_once __DIR__ . '/../../views/userView/TableView.php';

class DonView {
    private $donController;

    public function __construct() {
        $this->donController = new DonController();
    }

    public function displayDons() {
        echo '<div class="mb-6">';
        

        // Récupérer les dons
        $dons = $this->donController->getAllDons();
        
        // Transformer les données
        $formattedDons = array_map(function($don) {
    return [
        'id' => htmlspecialchars($don['id']),
        'user' => htmlspecialchars($don['user']),
        'amount' => number_format((float)$don['amount'], 2) . ' DA',
        'date' => date('d/m/Y', strtotime($don['payment_date'])),
        'description' => htmlspecialchars($don['description']),
        
        'receipt' => $don['file_path']
            ? '<a href="' . BASE_URL . '/uploads/receipts/' . htmlspecialchars($don['file_path']) . 
              '" class="text-blue-600 hover:underline" target="_blank">Voir le reçu</a>'
            : 'Non fourni'
    ];
}, $dons);


        // Définir les colonnes
        $columns = [
            ['label' => 'Donateur', 'field' => 'user'],
            ['label' => 'Montant', 'field' => 'amount'],
            ['label' => 'Date', 'field' => 'date'],
            ['label' => 'Description', 'field' => 'description'],
            
            ['label' => 'Reçu', 'field' => 'receipt']
        ];

        // Définir les actions
        $actions = [
            function($row) {
                if (!empty($row['id'])) {
                    return sprintf(
                        '<div class="flex space-x-2">
                            <a href="%s/don/validate?id=%s" class="text-green-600 hover:text-green-800 hover:underline">
                                Valider
                            </a>
                            <a href="%s/don?id=%s" class="text-blue-600 hover:text-blue-800 hover:underline">
                                Détails
                            </a>
                        </div>',
                        BASE_URL,
                        htmlspecialchars($row['id']),
                        BASE_URL,
                        htmlspecialchars($row['id'])
                    );
                }
                return '';
            }
        ];

        // Afficher le tableau
        echo '<div class="don-table-container">';
        $tableView = new TableView();
        $tableView->displayTable($formattedDons, $columns, $actions);
        echo '</div>';

        // Script pour les filtres
        ?>
        <script>
            $(document).ready(function() {
                initializeTableFilters('.don-table-container', [
                    { label: 'Donateur', columnIndex: 1 },
                    { label: 'Statut', columnIndex: 5 }
                ]);
            });
        </script>
        <?php
    }

    private function getStatusBadge($status) {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">En attente</span>',
            'approved' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Approuvé</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Rejeté</span>'
        ];
        return $badges[$status] ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Inconnu</span>';
    }
}
?>
