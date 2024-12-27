<?php

require_once __DIR__ . '/../models/EventModel.php';

class EventController {
    private $model;

    public function __construct() {
        $this->model = new EventModel();
    }

    public function getEvents() {
        return [
            'events' => $this->model->getLatestEventsByType('event'),
            'activities' => $this->model->getLatestEventsByType('activity')
        ];
    }
}
?>