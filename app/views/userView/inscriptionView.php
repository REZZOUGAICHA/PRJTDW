<?php
require_once __DIR__ . '/../../controllers/inscriptionController.php';
require_once __DIR__ . '/../../controllers/topbarController.php';
require_once __DIR__ . '/../../controllers/navbarController.php';
require_once __DIR__ . '/../../helpers/FileUploadHelper.php';



class InscriptionView {
    private $inscriptionController;
    
    public function __construct() {
        $this->inscriptionController = new InscriptionController();
    }
    
    public function displayInscriptionForm() {
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            
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
                    
                    <form method="POST" action="" enctype="multipart/form-data" class="mt-8 space-y-6">
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
                        </div>

                        <div>
                            <button type="submit" 
                                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Register
                            </button>
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
            $result = $this->inscriptionController->handleLogin($_POST['email'], $_POST['password']);
            
            if (isset($result['success'])) {
                $message = '<div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">' . $result['success'] . '</div>';
                $redirect = $result['redirect'];
            } elseif (isset($result['error'])) {
                $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">' . $result['error'] . '</div>';
            }
        }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100">
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-md">
                <div>
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Login to your account
                    </h2>
                </div>

                <?php echo $message; ?>
                
                <form method="POST" action="" class="mt-8 space-y-6">
                    <div class="rounded-md shadow-sm space-y-4">
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
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    </html>
    <?php
}

}
?>