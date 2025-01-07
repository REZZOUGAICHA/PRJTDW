<?php

require_once __DIR__ . '/../models/NewsModel.php';

class NewsController {
    private $model;

    public function __construct() {
        $this->model = new AnnounceModel();
    }

    public function getAnnounces() {
        return [
            'announces' => $this->model->getLatestAnnounces(3) 
        ];
    }
    public function getAllAnnounces() {
    return [
        'announces' => $this->model->getAllAnnounces() // Get all announcements
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
}
?>
