<?php
require_once __DIR__ . '/../models/InscriptionModel.php';

if (isset($_GET['user_id'])) {
    $model = new InscriptionModel();
    $connection = $model->db->connexion();

    try {
        $query = "SELECT profile_picture FROM user WHERE id = :user_id";
        $params = [':user_id' => $_GET['user_id']];
        $result = $model->db->request($connection, $query, $params);

        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row['profile_picture']) {
                // Get the image content from the file path
                $filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $row['profile_picture'];

                // Debugging: Log the full file path you're trying to access
                error_log("Attempting to access file: " . $filePath);

                // Check if the file exists
                if (file_exists($filePath)) {
                    // Detect mime type
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->file($filePath);

                    // Set the correct headers for the image
                    header('Content-Type: ' . $mimeType);
                    header('Content-Length: ' . filesize($filePath));

                    // Output the image content
                    readfile($filePath);
                    exit;
                } else {
                    // Debugging: Log if the file does not exist
                    error_log("File does not exist: " . $filePath);

                    // If the file doesn't exist, serve a default image
                    header('Content-Type: image/jpeg');
                    readfile(__DIR__ . '/../uploads/default-profile.jpg'); // Path to your default image
                    exit;
                }
            }
        }

        // If no profile picture found, return a default image
        header('Content-Type: image/jpeg');
        readfile(__DIR__ . '/../uploads/default-profile.jpg'); // Path to your default image

    } catch (Exception $e) {
        // Log the error message if something goes wrong
        error_log('Error retrieving image: ' . $e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
    } finally {
        $model->db->deconnexion();
    }
}
?>
