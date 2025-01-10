<?php
require_once __DIR__ . '/../../controllers/MembershipController.php';
require_once __DIR__ . '/../../helpers/FileUploadHelper.php';
require_once __DIR__ . '/../../helpers/SessionHelper.php';

class MembershipView {
    public function display($cardTypes, $hasPendingRequest = false) {
        ?>
        <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-teal-700">
                        <h1 class="text-xl font-bold text-gray-800">Demande de Carte de Membre</h1>
                    </div>

                    <?php if ($hasPendingRequest): ?>
                        <div class="p-6">
                            <div class="p-4 text-sm text-amber-700 bg-amber-100 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <!-- Warning icon -->
                                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium">Demande en cours de traitement</p>
                                        <p class="mt-1">Vous avez déjà une demande de carte de membre en attente. Veuillez patienter pendant que nous traitons votre demande. Nous vous contacterons dès que possible.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Affichage des messages -->
                        <?php if (!empty($_SESSION['success'])): ?>
                            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                                <?php 
                                    echo $_SESSION['success']; 
                                    unset($_SESSION['success']); 
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['error'])): ?>
                            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                                <?php 
                                    echo $_SESSION['error']; 
                                    unset($_SESSION['error']); 
                                ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo BASE_URL; ?>/membership?action=apply" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type de Carte</label>
                                <select name="card_type_id" required
    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
    <option value="" disabled selected>Choisir un type de carte</option>
    <?php foreach ($cardTypes as $cardType): ?>
        <option value="<?php echo $cardType['id']; ?>">
            <?php echo htmlspecialchars($cardType['name']); ?> - <?php echo number_format($cardType['annual_fee'], 2); ?> DA/an
        </option>
    <?php endforeach; ?>
</select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Carte d'Identité</label>
                                <input type="file" name="id_card" accept=".pdf,.jpg,.jpeg,.png" required
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                                        file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                <p class="mt-1 text-sm text-gray-500">PDF, JPG ou PNG (max. 5MB)</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reçu</label>
                                <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png" required
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                                        file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                <p class="mt-1 text-sm text-gray-500">PDF, JPG ou PNG (max. 5MB)</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Remarques (facultatif)</label>
                                <textarea name="notes" rows="4"
                                    placeholder="Ajoutez des remarques si nécessaire..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"></textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="px-4 py-2 text-sm font-medium text-black bg-teal-600 rounded-md hover:bg-teal-700">
                                    Soumettre la Demande
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>