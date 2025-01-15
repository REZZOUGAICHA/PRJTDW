<?php
class AdminConnView {
    private $adminConnController;
    
    public function __construct() {
        $this->adminConnController = new AdminConnController();
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    public function displayLoginForm() {
        $message = '';
        
        error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
        error_log("Form Action URL: " . BASE_URL . '/admin/connection');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("POST Data: " . print_r($_POST, true));
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">Invalid form submission.</div>';
                error_log("CSRF token validation failed");
            } else {
                error_log("Processing login form submission");
                $result = $this->adminConnController->handleLogin($_POST['name'], $_POST['password']);
                error_log("Login result: " . print_r($result, true));
                
                if (isset($result['error'])) {
                    $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">' . $result['error'] . '</div>';
                }
            }
        }
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin Login</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-100">
            <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-md">
                    <div>
                        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                            Admin Login
                        </h2>
                    </div>

                    <?php echo $message; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>/admin/connection" class="mt-8 space-y-6">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="rounded-md shadow-sm space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" 
                                       name="name" 
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