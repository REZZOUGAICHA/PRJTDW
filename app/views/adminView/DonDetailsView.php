<?php

class DonDetailsView {
    public function displayDonDetails($don) {
        if (!$don) {
            echo "<p>Don non trouvé.</p>";
            return;
        }

        ?>
        <div class="min-h-screen bg-gray-100 py-8">
            <div class="max-w-4xl mx-auto px-6">
                <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-800">Détails du Don</h1>
                        <div class="space-x-3">
                            <a href="<?php echo BASE_URL; ?>/admin/dons?action=accept&id=<?php echo $don['id']; ?>" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                Accepter
                            </a>
                            <a href="<?php echo BASE_URL; ?>/admin/dons?action=refuse&id=<?php echo $don['id']; ?>" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-black bg-red-600 rounded hover:bg-red-700">
                                Refuser
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Informations du Don</h2>
                        <p class="text-gray-700"><strong>Utilisateur:</strong> <?php echo htmlspecialchars($don['first_name'] . ' ' . $don['last_name']); ?></p>

                        <p class="text-gray-700"><strong>Montant:</strong> <?php echo htmlspecialchars($don['amount']); ?> DA</p>
                        <p class="text-gray-700"><strong>Date:</strong> <?php echo htmlspecialchars($don['payment_date']); ?></p>
                        <p class="text-gray-700"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($don['description'])); ?></p>
                        <p class="text-gray-700"><strong>Statut:</strong> <?php echo htmlspecialchars($don['status']); ?></p>

                        <?php if (!empty($don['file_path'])): ?>
                            <h2 class="text-xl font-semibold text-gray-800 mt-4">Reçu</h2>
                            <a href="<?php echo htmlspecialchars($don['file_path']); ?>" target="_blank" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                Voir le Reçu
                            </a>
                        <?php else: ?>
                            <p class="text-gray-700">Aucun reçu trouvé.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
