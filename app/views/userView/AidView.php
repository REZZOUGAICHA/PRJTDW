<?php
require_once __DIR__ . '/../../helpers/FileUploadHelper.php';
require_once __DIR__ . '/../../controllers/AidController.php';

class AidRequestView {
    public function displayAidRequestFormAndFiles($aidTypes) {
        ?>
        <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Aid Request Form -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-800">
                        <h1 class="text-xl font-bold text-black">Soumettre une demande d'aide</h1>
                    </div>

                    <!-- Display messages -->
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

                    <form action="<?php echo BASE_URL; ?>/aide" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                        <!-- Aid Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type d'aide</label>
                            <select name="aid_type_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">Sélectionnez un type d'aide</option>
                                <?php foreach ($aidTypes as $aidType): ?>
                                    <option value="<?php echo htmlspecialchars($aidType['id']); ?>">
                                        <?php echo htmlspecialchars($aidType['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléchargez vos fichiers</label>
                            <input type="file" name="files[]" multiple
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                                        file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <p class="mt-1 text-sm text-gray-500">Vous pouvez télécharger plusieurs fichiers (max. 10MB chacun).</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                                Soumettre la demande
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Files Section -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-800">
                        <h1 class="text-xl font-bold text-black">Types d'aide et fichiers requis</h1>
                    </div>
                    <div class="p-6 space-y-6">
                        <?php foreach ($aidTypes as $aidType): ?>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    <?php echo htmlspecialchars($aidType['name']); ?>
                                </h2>
                                <p class="text-sm text-gray-600">
                                    <?php echo htmlspecialchars($aidType['description']); ?>
                                </p>
                                <details class="mt-4">
                                    <summary class="text-sm font-medium text-gray-700 cursor-pointer">
                                        Fichiers requis
                                    </summary>
                                    <ul class="list-disc pl-5 text-sm text-gray-600 mt-2">
                                        <?php foreach ($aidType['files'] as $file): ?>
                                            <li>
                                                <strong><?php echo htmlspecialchars($file['name']); ?>:</strong>
                                                <?php echo htmlspecialchars($file['description']); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </details>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>