<?php
class HeaderHelper {
    public static function renderHeader($title = 'My Website') {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($title); ?></title>
            
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        </head>
        <body class="bg-gray-100">
        <?php
    }

    public static function renderFooter() {
        ?>
        </body>
        </html>
        <?php
    }
}
?>