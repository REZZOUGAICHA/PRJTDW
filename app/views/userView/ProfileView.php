<?php 
require_once __DIR__ . '/../../controllers/UserController.php';

class ProfileView {
    private $userController;
    
    public function __construct() {
        $this->userController = new UserController();
    }
    
    public function displayProfile() {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Connection');
            exit;
        }

        $userId = SessionHelper::get('user_id');
        $user = $this->userController->getUser($userId);
            $userController = new UserController();
    $userId = htmlspecialchars(SessionHelper::get('user_id'));
    $profilePicture = $userController->getUserProfilePicture($userId);
        $isEditing = isset($_GET['edit']) && $_GET['edit'] === 'true';
        ?>
        <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <!-- Profile Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                        <h1 class="text-xl font-bold text-white">Mon Profil</h1>
                    </div>
 <div class="flex justify-between ">    <!-- big container  -->
   
                    <div class="p-6 ">
                        <!-- Profile Header Section -->
                        <div class="flex items-start space-x-6 mb-6 pb-6 border-b border-gray-200">
                            <div class="flex-shrink-0">
                                <img src="<?php echo htmlspecialchars($profilePicture); ?>" 
                                 alt="Profile Picture" 
                                 class="w-20 h-20 rounded-full border-2 border-gray-300 object-cover">
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900">
                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                </h2>
                                <p class="text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                                <?php if (!$isEditing): ?>
                                    <div class="mt-4 mb-4">
                                        <a href="<?php echo BASE_URL; ?>/profile?edit=true" 
                                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            Modifier le profil
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Additional Buttons for Members -->
                        <?php if ($user['user_type'] === 'member'): ?>
                            <div class="mt-6 flex space-x-4 ">
                                <a href="<?php echo BASE_URL; ?>/history" 
                                   class="px-4 py-2 mb-4 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                    Historique
                                </a>
                                <a href="<?php echo BASE_URL; ?>/favorite-partners" 
                                   class="px-4 py-2 mb-4 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                                    Partenaires Favoris
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($isEditing): ?>
                            <!-- Edit Form -->
                            <form action="<?php echo BASE_URL; ?>/profile/update" method="POST" class="space-y-6">
                                <input type="hidden" name="action" value="update_profile">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                        <input type="text" name="first_name" 
                                               value="<?php echo htmlspecialchars($user['first_name']); ?>"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                                        <input type="text" name="last_name" 
                                               value="<?php echo htmlspecialchars($user['last_name']); ?>"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" name="email" 
                                               value="<?php echo htmlspecialchars($user['email']); ?>"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Photo de profil</label>
                                        <input type="file" name="profile_picture" accept="image/*"
                                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-3 pt-6 border-t">
                                    <a href="<?php echo BASE_URL; ?>/profile" 
                                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        Annuler
                                    </a>
                                    <button type="submit" 
                                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                        Sauvegarder les modifications
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <!-- Display Profile -->
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Type de compte</h3>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <?php echo ucfirst(htmlspecialchars($user['user_type'] ?? 'Standard')); ?>
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Date d'inscription</h3>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <?php echo date('d/m/Y', strtotime($user['registration_date'])); ?>
                                        </p>
                                    </div>

                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Statut du compte</h3>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <?php echo $user['is_active'] ? 'Actif' : 'Inactif'; ?>
                                        </p>
                                    </div>
                                </div>

                                <?php if ($user['user_type'] !== 'member'): ?>
                                    <div class="mt-6 pt-6 border-t">
                                        <div class="bg-yellow-50 p-4 rounded-lg">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-yellow-800">Compte non-membre</h3>
                                                    <div class="mt-2 text-sm text-yellow-700">
                                                        <p>Devenez membre pour accéder à tous les avantages!</p>
                                                    </div>
                                                    <div class="mt-4">
                                                        <a href="<?php echo BASE_URL; ?>/membership" 
                                                           class="text-sm font-medium text-yellow-800 hover:text-yellow-900">
                                                            En savoir plus <span aria-hidden="true">&rarr;</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6 mr-10">  <!--card div -->
                                <?php
                                if ($user['user_type'] === 'member'):
                                    require_once __DIR__ . '/../../controllers/MemberCardController.php';
                                    $memberCardController = new MemberCardController();
                                    $memberCardController->displayMemberCard($user['id']);
                                endif;
                                ?>
                    </div>
                    </div>  <!--big div end -->
                </div>
            </div>
        </div>
        <?php
    }
}
?>