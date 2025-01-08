
<?php
require_once  'cardView.php';
require_once __DIR__ . '/../../controllers/NewsController.php';

class NewsView {
    private $newsController;
    private $cardView;

    public function __construct() {
        $this->newsController = new NewsController();
        $this->cardView = new CardView();
    }

    public function announcesView() {
        $data = $this->newsController->getAllAnnounces(); // Get all announcements
        ?>
        <div class="w-full">
            <?php 
            // announces
            $this->cardView->displaySection($data['announces'], 'All Announcements', [
                'title' => 'name',
                'description' => 'description',
                'image' => 'picture_url',
            ]);
            ?>
        </div>
        <?php
    }

    public function eventsView() {
    $eventController = new EventController();
    $data = $eventController->getAllEvents(); // Get all events
    $cardView = new CardView();
    ?>
    <div class="w-full">
        <?php 
        // events
        $cardView->displaySection($data['events'], 'All Events', [
            'title' => 'event_name',
            'description' => 'event_description',
            'date' => 'event_date',
            'image' => 'file_path',
            'link' => 'link'
        ]);
        
        // Display all activities
        $cardView->displaySection($data['activities'], 'All Activities', [
            'title' => 'event_name',
            'description' => 'event_description',
            'date' => 'event_date',
            'image' => 'file_path'
        ]);
        ?>
    </div>
    <?php
}

}
?>
