<?php

require_once __DIR__ . '/../models/NewsModel.php';

class NewsController {
    private $model;
    private $fileUploader;

    public function __construct() {
        $this->model = new AnnounceModel();
        $this->fileUploader = new FileUploadHelper('uploads/announces/');
    }

    public function getAnnounces() {
        return [
            'announces' => $this->model->getLatestAnnounces(3) 
        ];
    }
    

    public function showAnnouncesLanding() {
        require_once __DIR__ . '/../Views/userView/LandingView.php';
        $view = new LandingView();
        $view->announcesView();
    }

    public function showAnnouncesNews() {
        require_once __DIR__ . '/../Views/userView/NewsView.php';
        $view = new NewsView();
        $view->announcesView();
    }

    public function showNewsDetails($id) {
        require_once __DIR__ . '/../Views/adminView/NewsDetailsView.php';
        $view = new NewsDetailView();
        $view->displayNewsDetail($id);
    }

   
    public function showNewsForAdmin() {
        require_once __DIR__ . '/../views/adminView/NewsView.php';
        $view = new NewsView();
        $view->displaynews()    ;
    }

    public function showCreateNewsForm() {
        require_once __DIR__ . '/../views/adminView/NewsView.php';
        $view = new NewsView();
        $view->displayCreateNewsForm();
    }

    

    // Data methods
    public function getAllAnnounces() {
        return [
            'announces' => $this->model->getAllAnnounces()
        ];
    }

    public function getnewsById($id) {
        return $this->model->getAnnounceById($id);
    }

    // CRUD operations
    public function handleNewsCreate($postData, $files) {
    try {
        $picture_url = '';
        if (!empty($files['image']['tmp_name'])) {  // Changed from 'picture' to 'image'
            $uploadResult = $this->fileUploader->saveFile($files['image']);
            if (!$uploadResult['success']) {
                throw new Exception('File upload failed: ' . $uploadResult['error']);
            }
            $picture_url = $uploadResult['filePath'];
        }
        
        if ($this->model->createAnnounce($postData['title'], $postData['description'], $picture_url)) {  // Changed from 'name' to 'title'
            header('Location: ' . BASE_URL . '/admin/news');
            exit;
        }
        throw new Exception('Failed to create announcement');
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: ' . BASE_URL . '/admin/news?action=create');  // Fixed redirect URL
        exit;
    }
}

  public function handleNewsUpdate($id, $postData, $files) {
    try {
        $announce = $this->model->getAnnounceById($id);
        if (!$announce) {
            throw new Exception('Announcement not found');
        }
        
        $picture_url = $announce['picture_url'];
        
        // Check if a new picture is uploaded
        if (!empty($files['picture']['tmp_name'])) {
            // Use FileUploadHelper to handle the file upload
            $fileUploadHelper = new FileUploadHelper('uploads/announces/');
            $uploadResult = $fileUploadHelper->saveFile($files['picture']);
            
            if (!$uploadResult['success']) {
                throw new Exception('File upload failed: ' . $uploadResult['error']);
            }
            
            // Set the new picture URL
            $picture_url = $uploadResult['filePath'];
            
            // Delete the old picture if it exists
            if (!empty($announce['picture_url'])) {
                $oldFilePath = str_replace(BASE_URL, '', $announce['picture_url']);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        }
        
        // Update the announcement in the database
        if ($this->model->updateAnnounce($id, $postData['name'], $postData['description'], $picture_url)) {
            header('Location: ' . BASE_URL . '/admin/news?id=' . $id);
            exit;
        }
        throw new Exception('Failed to update announcement');
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: ' . BASE_URL . '/admin/news?id=' . $id . '&edit=true');
        exit;
    }
}

    public function deleteNews($id) {
        try {
            $announce = $this->model->getAnnounceById($id);
            if (!$announce) {
                throw new Exception('Announcement not found');
            }
            
            if (!empty($announce['picture_url'])) {
                $filePath = 'uploads/announces/' . $announce['picture_url'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            if ($this->model->deleteAnnounce($id)) {
                header('Location: ' . BASE_URL . '/admin/news');
                exit;
            }
            throw new Exception('Failed to delete announcement');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/news/' . $id);
            exit;
        }
    }
}
?>
