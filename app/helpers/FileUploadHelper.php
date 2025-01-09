<?php
require_once 'Database.php';
class FileUploadHelper {
    
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    private $maxFileSize = 5242880; // 5MB
    private $uploadDirectory;
    

    public function __construct($uploadDirectory = 'uploads/') {
        $this->uploadDirectory = rtrim($uploadDirectory, '/') . '/';
        if (!file_exists($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0777, true);
        }
    }
//---------------------------------upload file---------------------------------
    
    

    public function uploadFile($file) {
        try {
            
            
            // Read file 
            $fileContent = file_get_contents($file['tmp_name']);
            if ($fileContent === false) {
                throw new Exception('Failed to read file contents');
            }
            
            return [
                'success' => true,
                'fileContent' => $fileContent,
                'mimeType' => $file['type']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

//---------------------------------generate unique file name---------------------------------
   

    private function generateUniqueFileName($originalName) {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid() . '_' . time() . '.' . $extension;
    }

    public function setAllowedTypes($types) {
        $this->allowedTypes = $types;
    }

    public function setMaxFileSize($size) {
        $this->maxFileSize = $size;
    }
// for profile pic --------------------------------------------------------------------------
//move this to a model later  -using blob here 
    public function displayImage($userId) {
        try {
            
            
            $db = new Database();
            $connection = $db->connexion();
            
            $query = "SELECT profile_picture FROM user WHERE id = :user_id";
            $params = [':user_id' => $userId];
            
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row && $row['profile_picture']) {
                // Detect mime type from the binary data
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $finfo->buffer($row['profile_picture']);
                
                // Set proper headers
                header('Content-Type: ' . $mime_type);
                echo $row['profile_picture'];
            } else {
                // If no image found, return a default image 
                header('Content-Type: image/jpeg');
                // readfile(__DIR__ . '/../public/default-profile.jpg');  change this and add a default pic :=)
            }
            
            $db->deconnexion();
            
        } catch (Exception $e) {
            error_log('Error displaying image: ' . $e->getMessage());
            header('HTTP/1.1 500 Internal Server Error');
        }
    }


   public function saveFile($file, $subdirectory = '') {
        try {
            $targetDir = $this->uploadDirectory . $subdirectory;
            
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $fileName = $this->generateUniqueFileName($file['name']);
            $targetPath = $targetDir . $fileName;
            
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                throw new Exception('Failed to move uploaded file');
            }
            
            $relativePath = BASE_URL . '/' . $this->uploadDirectory . $subdirectory . $fileName;
            
            return [
                'success' => true,
                'filePath' => $relativePath
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

}

?>