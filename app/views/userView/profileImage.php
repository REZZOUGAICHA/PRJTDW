<?php
require_once __DIR__ . '/../../models/InscriptionModel.php';

if (isset($_GET['user_id'])) {
    $model = new InscriptionModel();
    $connection = $model->db->connexion();
    
    try {
        $query = "SELECT profile_picture FROM user WHERE id = :user_id";
        $params = [':user_id' => $_GET['user_id']];
        $result = $model->db->request($connection, $query, $params);
        
        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row['profile_picture']) {
                // Detect mime type from the binary data(cus im useing blob here)
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $finfo->buffer($row['profile_picture']);
                
                header('Content-Type: ' . $mime_type);
                echo $row['profile_picture'];
                exit;
            }
        }
        
        // If no image found or empty, return a default image - add 
        header('Content-Type: image/jpeg');
        readfile(__DIR__ . '/../uploads/default.png');
        
    } catch (Exception $e) {
        error_log('Profile image error: ' . $e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
    } finally {
        $model->db->deconnexion();
    }
}