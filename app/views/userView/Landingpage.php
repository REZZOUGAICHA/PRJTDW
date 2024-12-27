<?php

require_once 'LandingView.php';


$landing = new LandingView();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="../../../public/css/output.css">
</head>
<body>
    <?php
    $landing->navbarView();
    $landing->diaporamaView();
    $landing->eventsView();
    $landing->discountsView();

    ?>
</body>
</html>
