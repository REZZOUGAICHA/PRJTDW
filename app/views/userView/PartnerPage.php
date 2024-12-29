<?php

require_once 'LandingView.php';
require_once 'footerView.php';
require_once 'partnerView.php';
require_once 'submenuView.php';



$landing = new LandingView();
$footer = new FooterView();
$submenu = new SubmenuView();
$partner = new partnerView();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partners Page</title>
    <link rel="stylesheet" href="../../../public/css/output.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="../../../public/js/search.js"></script>

</head>
<body>
    <?php
    $landing->displayTopbar();
    $submenu->displaySubmenu('partner');
    $partner->displaypartner();
    $footer->displayFooterMenu();
    
    


    ?>
</body>
</html>
