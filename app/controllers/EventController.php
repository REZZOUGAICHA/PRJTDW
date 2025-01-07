<?php

require_once __DIR__ . '/../models/EventModel.php';


class EventController {
    private $model;

    public function __construct() {
        $this->model = new EventModel();
    }

    // Fetch 3 latest events for the landing page
    public function getLatestEvents() {
        return [
            'events' => $this->model->getLatestEventsByType('event', 3), // Limit to 3 events
            'activities' => $this->model->getLatestEventsByType('activity', 3) // Limit to 3 activities
        ];
    }

    // Fetch all events for the news page
    public function getAllEvents() {
        return [
            'events' => $this->model->getAllEventsByType('event'),
            'activities' => $this->model->getAllEventsByType('activity')
        ];
    }

    public function showEventsLanding() {
        require_once __DIR__ . '/../Views/userView/LandingView.php';
        $view = new LandingView();
        $view->eventsView();
    }

    public function showEventsNews() {
        require_once __DIR__ . '/../Views/userView/NewsView.php';
        $view = new NewsView();
        $view->eventsView();
    }
}
?>