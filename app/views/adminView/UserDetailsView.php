<?php
require_once __DIR__ . '/../../controllers/userController.php';

class UserDetailsView {
    private $userController;

    public function __construct() {
        $this->userController = new UserController();
    }

    public function displayUserDetail($userId) {

         error_log("UserDetailsView::displayUserDetail called with userId: " . $userId);  // Add this
    if (!isset($userId)) {
        error_log("UserId not set");  // Add this
        header('Location: ' . BASE_URL . '/admin/membre');
        exit;
    }

    $user = $this->userController->getUserInfoById($userId);
    error_log("User data retrieved: " . print_r($user, true));

        $user = $this->userController->getUserInfoById($userId);
        ?>
        <div class="min-h-screen bg-gray-100 py-8">
            <div class="max-w-4xl mx-auto px-6">
                <div class="bg-white shadow-lg rounded-lg border border-gray-200">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-800">Détails de l'utilisateur</h1>
                        <button onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) window.location.href='<?php echo BASE_URL; ?>/admin/membre?action=delete&id=<?php echo $userId; ?>'"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-black rounded hover:bg-red-700">
                            Supprimer
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="space-y-6">
                            <!-- Personal Information -->
                            <div class="border-b border-gray-200 pb-6">
                                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations personnelles</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['first_name']); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['last_name']); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['email']); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date d'inscription</label>
                                        <p class="mt-1 text-gray-900"><?php echo date('d/m/Y', strtotime($user['registration_date'])); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Status -->
                            <div class="border-b border-gray-200 pb-6">
                                <h2 class="text-xl font-semibold text-gray-800 mb-4">Statut du compte</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Type d'utilisateur</label>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['user_type']); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                                        <p class="mt-1">
                                            <span class="px-2 py-1 text-sm rounded-full <?php echo $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo $user['is_active'] ? 'Actif' : 'Inactif'; ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Statut d'adhésion</label>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['membership_status']); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Information (if exists) -->
                            <?php if ($user['card_type']): ?>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations de la carte</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Type de carte</label>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['card_type']); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Numéro de carte</label>
                                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($user['card_number']); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date d'expiration</label>
                                        <p class="mt-1 text-gray-900"><?php echo date('d/m/Y', strtotime($user['expiration_date'])); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>