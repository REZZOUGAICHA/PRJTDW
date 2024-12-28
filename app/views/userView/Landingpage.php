<?php

require_once 'LandingView.php';
require_once 'footerView.php';


$landing = new LandingView();
$footer = new FooterView();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="../../../public/css/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>
    <?php
    $landing->navbarView();
    $landing->diaporamaView();
    $landing->eventsView();
    $landing->discountsView();
    $landing->offersView();
    $footer->displayFooterMenu();


    ?>
</body>
</html>
