<?php
if (!file_exists(__DIR__ . '/../helpers/phpqrcode/qrlib.php')) {
    die('qrlib.php not found');
}

require_once(__DIR__ . '/../helpers/phpqrcode/qrlib.php');
class QRCodeHelper {
    public static function generateQRCode($data) {
        $directory = __DIR__ . "/../../public/qr_codes";
        
        // Create directory if it doesn't exist
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
            chmod($directory, 0777);
        }
        
        // Use a consistent filename based on the card number
        $filename = 'card_' . preg_replace('/[^A-Za-z0-9\-]/', '', $data);
        $filePath = $directory . "/{$filename}.png";
        
        // Generate QR code only if it doesn't exist
        if (!file_exists($filePath)) {
            // Check if directory is writable
            if (!is_writable($directory)) {
                throw new Exception("Directory {$directory} is not writable");
            }
            
            \QRcode::png($data, $filePath);
            
            // Verify file was created
            if (!file_exists($filePath)) {
                throw new Exception("Failed to create QR code file");
            }
        }
        
        // Return the web-accessible path
        return '/qr_codes/' . $filename . '.png';
    }
}
?>
