<?php
require_once __DIR__ . '/../../controllers/DonController.php';
require_once __DIR__ . '/../../helpers/FileUploadHelper.php';

class DonFormView {
    public function display() {
        // Pour l'affichage 
        $ccpNumber = "12345 6789 01234567"; 
        $baridiMobNumber = "13 770 123 456"; 

        ?>
        <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                        <h1 class="text-xl font-bold text-gray-800">Faire un don</h1>
                    </div>

                    <!-- Affichage des informations  -->
                    <div class="p-6 bg-gray-100 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Informations de paiement</h2>
                        <p class="mt-2 text-sm text-gray-600">
                            <strong>Numéro CCP :</strong> <?php echo $ccpNumber; ?><br>
                            <strong>Numéro BaridiMob :</strong> <?php echo $baridiMobNumber; ?>
                        </p>
                    </div>

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

                    <form action="<?php echo BASE_URL; ?>/don" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Montant (DA)</label>
                            <input type="number" name="amount" step="0.01" min="0" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4" required
                                placeholder="Comment souhaitez-vous que votre don soit utilisé ?"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Reçu</label>
                            <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png" required
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-sm text-gray-500">PDF, JPG ou PNG (max. 5MB)</p>
                        </div>
                        <!-- link this with history later  -->
                        <div class="flex items-center">
                            <input type="checkbox" name="save_history" id="save_history"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="save_history" class="ml-2 block text-sm text-gray-900">
                                Sauvegarder dans mon historique de dons
                            </label>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                Soumettre le don
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
