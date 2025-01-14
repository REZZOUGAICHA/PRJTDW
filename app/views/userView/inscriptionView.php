<?php
require_once __DIR__ . '/../../controllers/inscriptionController.php';
require_once __DIR__ . '/../../controllers/topbarController.php';
require_once __DIR__ . '/../../controllers/navbarController.php';
require_once __DIR__ . '/../../helpers/FileUploadHelper.php';

class InscriptionView {
    private $inscriptionController;
    
    public function __construct() {
        $this->inscriptionController = new InscriptionController();
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    public function displayInscriptionForm() {
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">Invalid form submission.</div>';
                return;
            }

            $data = $_POST;
            
            // Validate password confirmation
            if ($data['password'] !== $data['password_confirmation']) {
                $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">Passwords do not match.</div>';
                return;
            }
            
            // Handle profile picture upload if present
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploadHelper = new FileUploadHelper();
                $uploadResult = $uploadHelper->uploadFile($_FILES['profile_picture']);
                
                if ($uploadResult['success']) {
                    $data['profile_picture'] = $uploadResult['fileContent'];
                } else {
                    $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">' . $uploadResult['error'] . '</div>';
                }
            }

            if (empty($message)) {
                $result = $this->inscriptionController->handleInscription($data);
                if (isset($result['success'])) {
                    $message = '<div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">' . $result['success'] . '</div>';
                    if (isset($result['redirect'])) {
                        header('Location: ' . $result['redirect']);
                        exit;
                    }
                } elseif (isset($result['error'])) {
                    $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">' . $result['error'] . '</div>';
                }
            }
        }
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Registration</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-100">
            <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-md">
                    <div>
                        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                            Create your account
                        </h2>
                    </div>

                    <?php echo $message; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/Inscription" enctype="multipart/form-data" class="mt-8 space-y-6">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="rounded-md shadow-sm space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Profile Picture</label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" 
                                           name="profile_picture" 
                                           accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" 
                                       name="first_name" 
                                       required 
                                       class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" 
                                       name="last_name" 
                                       required 
                                       class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" 
                                       name="email" 
                                       required 
                                       class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" 
                                       name="password" 
                                       required 
                                       class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       required 
                                       class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <button type="submit" 
                                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Register
                            </button>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600">
                                Already have an account? 
                                <a href="/Connection" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Login here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </body>
        </html>
        <?php
    }

    public function displayLoginForm() {
    $message = '';
    $redirect = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">Soumission de formulaire invalide.</div>';
            return;
        }

        // Choose which login handler to use based on user_type
        if ($_POST['user_type'] === 'partner') {
            $result = $this->inscriptionController->handlePartnerLogin($_POST['email'], $_POST['password']);
        } else {
            $result = $this->inscriptionController->handleLogin($_POST['email'], $_POST['password']);
        }
        
        if (isset($result['success'])) {
            $message = '<div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">' . $result['success'] . '</div>';
            if (isset($result['redirect'])) {
                header('Location: ' . $result['redirect']);
                exit;
            }
        } elseif (isset($result['error'])) {
            $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">' . $result['error'] . '</div>';
        }
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Connexion</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .hidden {
                display: none;
            }
        </style>
    </head>
    <body class="bg-gray-100">
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-md">
                <div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Connexion à votre compte
                    </h2>
                </div>

                <?php echo $message; ?>

                <!-- User Type Selection Buttons -->
                <div class="flex justify-center space-x-4 mb-8" id="userTypeSelection">
                    <button onclick="showForm('user')" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Utilisateur
                    </button>
                    <button onclick="showForm('partner')" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Partenaire
                    </button>
                </div>
                
                <!-- User Login Form -->
                <form id="userForm" method="POST" action="<?php echo BASE_URL; ?>/Connection" class="mt-8 space-y-6 hidden">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="user_type" value="user">
                    
                    <div class="rounded-md shadow-sm space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" 
                                   name="email" 
                                   required 
                                   class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            <input type="password" 
                                   name="password" 
                                   required 
                                   class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Se connecter
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">
                            Vous n'avez pas de compte ? 
                            <a href="<?php echo BASE_URL; ?>/Inscription" class="font-medium text-indigo-600 hover:text-indigo-500">
                                Inscrivez-vous ici
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Partner Login Form -->
                <form id="partnerForm" method="POST" action="<?php echo BASE_URL; ?>/Connection" class="mt-8 space-y-6 hidden">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="user_type" value="partner">
                    
                    <div class="rounded-md shadow-sm space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" 
                                   name="email" 
                                   required 
                                   class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            <input type="password" 
                                   name="password" 
                                   required 
                                   class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function showForm(type) {
            // Hide both forms
            document.getElementById('userForm').classList.add('hidden');
            document.getElementById('partnerForm').classList.add('hidden');
            
            // Show the selected form
            if (type === 'user') {
                document.getElementById('userForm').classList.remove('hidden');
            } else {
                document.getElementById('partnerForm').classList.remove('hidden');
            }
        }
        </script>
    </body>
    </html>
    <?php
}
}
?>