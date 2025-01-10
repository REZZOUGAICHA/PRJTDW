<?php
require_once __DIR__ . '/../../controllers/AidController.php';

class AidDetailsView {
    private $aidController;

    public function __construct() {
        $this->aidController = new AidController();
    }

    public function displayAidRequestDetails($aidRequest) {
        // If we received an ID instead of the full request data, fetch the data
        if (!is_array($aidRequest)) {
            if (!$aidRequest) {
                header('Location: ' . BASE_URL . '/admin/aide');
                exit;
            }
            $aidRequest = $this->aidController->getAidRequestById($aidRequest);
        }

        if (!$aidRequest) {
            echo "<p>Demande d'aide non trouvée.</p>";
            return;
        }
        ?>
        <div class="min-h-screen bg-gray-100 py-8">
            <div class="max-w-4xl mx-auto px-6">
                <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-800">Détails de la demande d'aide</h1>
                        <div class="space-x-3">
                            <a href="<?php echo BASE_URL; ?>/admin/aide?action=accept&id=<?php echo htmlspecialchars($aidRequest['id']); ?>" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                Accepter
                            </a>
                            <a href="<?php echo BASE_URL; ?>/admin/aide?action=refuse&id=<?php echo htmlspecialchars($aidRequest['id']); ?>" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                Refuser
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Aid Request Details -->
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">Informations de l'utilisateur</h2>
                                <p class="text-gray-700"><strong>Nom:</strong> <?php echo htmlspecialchars($aidRequest['first_name'] . ' ' . $aidRequest['last_name']); ?></p>
                                <p class="text-gray-700"><strong>Type d'aide:</strong> <?php echo htmlspecialchars($aidRequest['aid_type_name']); ?></p>
                                <p class="text-gray-700"><strong>Statut:</strong> <?php 
                                    $status = $aidRequest['status'] ?? 'En attente';
                                    $statusClass = '';
                                    switch($status) {
                                        case 'accepted':
                                            $status = 'Acceptée';
                                            $statusClass = 'text-green-600';
                                            break;
                                        case 'refused':
                                            $status = 'Refusée';
                                            $statusClass = 'text-red-600';
                                            break;
                                        default:
                                            $statusClass = 'text-yellow-600';
                                    }
                                    echo "<span class=\"font-medium {$statusClass}\">" . htmlspecialchars($status) . "</span>";
                                ?></p>
                                <p class="text-gray-700"><strong>Date de demande:</strong> <?php echo (new DateTime($aidRequest['created_at']))->format('d/m/Y H:i'); ?></p>
                            </div>

                            <!-- Documents -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">Documents fournis</h2>
                                <?php if (!empty($aidRequest['files'])): ?>
                                    <div class="space-y-2">
                                    <?php foreach ($aidRequest['files'] as $file): ?>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-gray-700"><?php echo htmlspecialchars($file['type_name']); ?>:</span>
                                            <a href="<?php echo htmlspecialchars($file['file_path']); ?>" target="_blank" 
                                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                                Voir le document
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-gray-700">Aucun document fourni.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}