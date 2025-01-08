<?php
require_once __DIR__ . '/../../helpers/FileUploadHelper.php';
require_once __DIR__ . '/../../controllers/AidController.php';

class AidRequestView {
    private $aidController;

    public function __construct() {
        $this->aidController = new AidController();
    }

    public function displayAidRequestForm($aidTypes) {
        ?>
        <div class="max-w-3xl mx-auto p-6">
            <?php $this->displayMessages(); ?>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-8">Faire une demande d'aide</h2>

                <!-- Progress Steps -->
                <div class="mb-12 flex justify-between relative">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center">1</div>
                    <div class="w-10 h-10 <?= isset($_POST['aid_type_id']) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' ?> rounded-full flex items-center justify-center">2</div>
                    <div class="w-10 h-10 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center">3</div>
                </div>

                <!-- Step 1: Aid Type Selection -->
                <form id="aidTypeForm" action="<?= BASE_URL ?>/aide" method="POST">
                    <div class="mb-8">
                        <label for="aidType" class="block text-sm font-medium text-gray-700 mb-2">Sélectionnez le type d'aide souhaité</label>
                        <select name="aid_type_id" id="aidType" class="w-full rounded-lg border-gray-300 py-3 pl-4 pr-10">
                            <option value="">--Sélectionnez un type d'aide--</option>
                            <?php foreach ($aidTypes as $aidType): ?>
                                <option value="<?= $aidType['id'] ?>" <?= (isset($_POST['aid_type_id']) && $_POST['aid_type_id'] == $aidType['id']) ? 'selected' : '' ?>><?= htmlspecialchars($aidType['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <!-- Step 2: File Upload -->
                <?php if (isset($_POST['aid_type_id']) && !empty($_POST['aid_type_id'])): ?>
                    <?php $fileTypes = $this->aidController->model->getFileTypesForAidType($_POST['aid_type_id']); ?>
                    <?php if (!empty($fileTypes)): ?>
                        <form action="<?= BASE_URL ?>/aide" method="POST" enctype="multipart/form-data" class="mt-8">
                            <input type="hidden" name="aid_type_id" value="<?= htmlspecialchars($_POST['aid_type_id']) ?>">

                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-gray-900">Documents requis :</h3>
                                <?php foreach ($fileTypes as $fileType): ?>
                                    <div class="file-upload-container">
                                        <label for="file_<?= $fileType['id'] ?>" class="cursor-pointer border-2 border-dashed border-gray-300 rounded-lg px-6 py-8 hover:border-blue-500 group">
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="mt-4">
                                                    <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($fileType['name']) ?></span>
                                                    <p class="text-xs text-gray-500 mt-1">PDF, JPEG ou PNG</p>
                                                </div>
                                            </div>
                                            <input type="file" name="files[<?= $fileType['id'] ?>]" id="file_<?= $fileType['id'] ?>" accept="<?= $this->getAcceptableFileTypes($fileType['name']) ?>" class="hidden required-file">
                                        </label>
                                    </div>
                                <?php endforeach; ?>

                                <button type="submit" id="submitBtn" disabled class="w-full py-3 px-4 border border-transparent rounded-md bg-blue-600 text-white">Soumettre la demande</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-700">Aucun document requis pour ce type d'aide.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aidTypeSelect = document.getElementById('aidType');
            if (aidTypeSelect) {
                aidTypeSelect.addEventListener('change', function() {
                    if (this.value) {
                        document.getElementById('aidTypeForm').submit();
                    }
                });
            }

            const fileInputs = document.querySelectorAll('.required-file');
            const submitBtn = document.getElementById('submitBtn');
            
            function updateSubmitButtonState() {
                const allFilesSelected = Array.from(fileInputs).every(input => input.files.length > 0);
                submitBtn.disabled = !allFilesSelected;
            }

            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const container = this.closest('.file-upload-container');
                    const fileInfo = container.querySelector('.file-info');
                    const uploadBox = container.querySelector('.border-dashed');

                    if (this.files.length > 0) {
                        const file = this.files[0];
                        fileInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                        fileInfo.classList.remove('hidden');
                        uploadBox.classList.add('border-blue-500', 'bg-blue-50');
                    } else {
                        fileInfo.classList.add('hidden');
                        uploadBox.classList.remove('border-blue-500', 'bg-blue-50');
                    }

                    updateSubmitButtonState();
                });
            });

            updateSubmitButtonState();
        });
        </script>
        <?php
    }

    private function displayMessages() {
        if (isset($_SESSION['success'])) {
            echo '<div class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800 flex items-center">' .
                '<svg class="h-5 w-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">' .
                '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>' .
                '</svg>' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        
        if (isset($_SESSION['error'])) {
            echo '<div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-800 flex items-center">' .
                '<svg class="h-5 w-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">' .
                '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>' .
                '</svg>' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
    }

    private function getAcceptableFileTypes($fileName) {
        $allowedTypes = [
            'Extrait de naissance' => 'application/pdf,image/jpeg,image/png',
            'Carte d\'identité' => 'application/pdf,image/jpeg,image/png',
            'Certificat de scolarité' => 'application/pdf,image/jpeg,image/png',
            'Reçu de paiement' => 'application/pdf,image/jpeg,image/png',
            'Justificatif de domicile' => 'application/pdf,image/jpeg,image/png',
            'Certificat médical' => 'application/pdf,image/jpeg,image/png',
            'Relevé de compte bancaire' => 'application/pdf,image/jpeg,image/png'
        ];
        return $allowedTypes[$fileName] ?? 'application/pdf,image/jpeg,image/png';
    }
}
?>
