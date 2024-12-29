<?php
class FileUploadHelper {
    private $uploadDir;
    
    public function __construct($uploadDir = 'uploads/') {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    public function uploadFile($file, $allowedTypes = [], $maxSize = 5242880) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload failed');
        }
        
        if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
            throw new Exception('File type not allowed');
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception('File too large');
        }
        
        $fileName = uniqid() . '_' . basename($file['name']);
        $destination = $this->uploadDir . $fileName;
        
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Failed to move uploaded file');
        }
        
        return $fileName;
    }
}
