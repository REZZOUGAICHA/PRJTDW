<?php
require_once __DIR__ . '/../../controllers/inscriptionController.php';

class InscriptionView {
    private $inscriptionController;
    
    public function __construct() {
        $this->inscriptionController = new InscriptionController();
    }
    
    public function displayInscriptionForm() {
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->inscriptionController->handleInscription($_POST);
            if (isset($result['success'])) {
                $message = '<div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px;">' . $result['success'] . '</div>';
            } elseif (isset($result['error'])) {
                $message = '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px;">' . $result['error'] . '</div>';
            }
        }
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Registration</title>
            <style>
                .container { max-width: 500px; margin: 50px auto; padding: 20px; }
                .form-group { margin-bottom: 15px; }
                label { display: block; margin-bottom: 5px; }
                input[type="text"], input[type="email"], input[type="password"] {
                    width: 100%;
                    padding: 8px;
                    margin-bottom: 10px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                }
                button {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                button:hover { background-color: #45a049; }
            </style>
        </head>
        <body>
            <div class="container">
                <?php echo $message; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label>First Name:</label>
                        <input type="text" name="first_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Last Name:</label>
                        <input type="text" name="last_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="password" required>
                    </div>
                    
                    <button type="submit">Register</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    }
}
?>
