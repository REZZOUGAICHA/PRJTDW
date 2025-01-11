<?php

class QRCodeHelper {
    public static function generateQRCode($data) {
        require_once '/phpqrcode/qrlib.php';
        
        // Make sure the directory exists
        $directory = __DIR__ . "/../../public/qr_codes";
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $filePath = $directory . "/{$data}.png";
        \QRcode::png($data, $filePath);
        
        return $filePath;
    }

}
?>
