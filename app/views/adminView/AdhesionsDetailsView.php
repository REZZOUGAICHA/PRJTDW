<?php
require_once __DIR__ . '/../../controllers/MembershipController.php';

class AdhesionsDetailsView {
    private $membershipController;

    public function __construct() {
        $this->membershipController = new MembershipController();
    }

    public function displayMembershipDetail($membershipId) {
        if (!isset($membershipId)) {
            header('Location: ' . BASE_URL . '/admin/adhesions');
            exit;
        }

        // Fetch membership request details (including receipt and ID card)
        $membership = $this->membershipController->getRequestById($membershipId);

        if (!$membership) {
            echo "<p>Demande d'adhésion non trouvée.</p>";
            return;
        }
        ?>
        <div class="min-h-screen bg-gray-100 py-8">
            <div class="max-w-4xl mx-auto px-6">
                <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-800">Détails de la demande d'adhésion</h1>
                        <div class="space-x-3">
                            <a href="<?php echo BASE_URL; ?>/admin/adhesions?action=accept&id=<?php echo $membershipId; ?>" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                Accepter
                            </a>
                            <a href="<?php echo BASE_URL; ?>/admin/adhesions?action=refuse&id=<?php echo $membershipId; ?>" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-black bg-red-600 rounded hover:bg-red-700 stroke-red-700">
                                Refuser
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Membership Details -->
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">Informations de l'utilisateur</h2>
                                <p class="text-gray-700"><strong>Nom:</strong> <?php echo htmlspecialchars($membership['first_name'] . ' ' . $membership['last_name']); ?></p>
                                <p class="text-gray-700"><strong>Type de carte:</strong> <?php echo htmlspecialchars($membership['card_name']); ?></p>
                                <p class="text-gray-700"><strong>Statut:</strong> <?php echo htmlspecialchars($membership['status']); ?></p>
                                <p class="text-gray-700"><strong>Date de demande:</strong> <?php echo htmlspecialchars($membership['application_date']); ?></p>
                                <p class="text-gray-700"><strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($membership['notes'])); ?></p>
                            </div>

                            <!-- Receipt -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">Reçu de paiement</h2>
                                <?php if (!empty($membership['receipt_path'])): ?>
                                    <a href="<?php echo htmlspecialchars($membership['receipt_path']); ?>" target="_blank" 
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Voir le reçu
                                    </a>
                                <?php else: ?>
                                    <p class="text-gray-700">Aucun reçu trouvé.</p>
                                <?php endif; ?>
                            </div>

                            <!-- ID Card -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">Carte d'identité</h2>
                                <?php if (!empty($membership['id_card_path'])): ?>
                                    <a href="<?php echo htmlspecialchars($membership['id_card_path']); ?>" target="_blank" 
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Voir la carte d'identité
                                    </a>
                                <?php else: ?>
                                    <p class="text-gray-700">Aucune carte d'identité trouvée.</p>
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
?>