<?php 
require_once __DIR__ . '/../../controllers/membershipController.php';
require_once __DIR__ . '/../../helpers/SessionHelper.php';
//--------------------------still not working-----------------------------------------------------
class MembershipView {
    private $cardTypes;
    
    public function __construct() {
        $db = new Database();
        $conn = $db->connexion();
        $stmt = $conn->query("SELECT * FROM cardtype ORDER BY annual_fee");
        $this->cardTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function displayMembershipForm() {
        ?>
        <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                        <h1 class="text-xl font-bold text-white">Demande d'Adhésion</h1>
                    </div>
                    


    <!-- ------------------------------------------------------------------------------------------->
    
        <div class="max-w-3xl mx-auto">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']); 
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']); 
                ?>
            </div>
        <?php endif; ?>


                    <form action="<?php echo BASE_URL; ?>/membership/submit" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                        <!-- Card Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Type de Carte</label>
                            <div class="space-y-4">
                                <?php foreach ($this->cardTypes as $card): ?>
                                    <div class="relative border rounded-lg p-4 hover:border-indigo-500">
                                        <label class="flex items-start">
                                            <input type="radio" name="card_type" value="<?php echo $card['id']; ?>" 
                                                    class="mt-1 h-4 w-4 text-indigo-600" required>
                                            <div class="ml-3">
                                                <span class="block font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($card['name']); ?>
                                                </span>
                                                <span class="block text-gray-500 text-sm">
                                                    <?php echo htmlspecialchars($card['description']); ?>
                                                </span>
                                                <span class="block text-indigo-600 font-medium mt-1">
                                                    <?php echo number_format($card['annual_fee'], 0, ',', ' '); ?> FCFA/an
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- File Uploads -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Carte d'Identité</label>
                                <div class="mt-1">
                                    <input type="file" name="id_card" accept="image/*,.pdf" required
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                                                file:rounded-full file:border-0 file:text-sm file:font-semibold
                                                file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <p class="mt-1 text-sm text-gray-500">Format JPG, PNG ou PDF. Maximum 5MB.</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reçu de Paiement</label>
                                <div class="mt-1">
                                    <input type="file" name="receipt" accept="image/*,.pdf" required
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                                                file:rounded-full file:border-0 file:text-sm file:font-semibold
                                                file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <p class="mt-1 text-sm text-gray-500">Format JPG, PNG ou PDF. Maximum 5MB.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6 border-t">
                            <button type="submit" 
                                    class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 
                                        rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 
                                        focus:ring-offset-2 focus:ring-indigo-500">
                                Soumettre la demande
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