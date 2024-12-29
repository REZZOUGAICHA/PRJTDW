<?php

require_once 'LandingView.php';
require_once 'footerView.php';
require_once 'partnerView.php';
require_once 'submenuView.php';
require_once 'offerView.php';


$landing = new LandingView();
$footer = new FooterView();
$submenu = new SubmenuView();
$offer = new offerView();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partners Page</title>
    <link rel="stylesheet" href="../../../public/css/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>
    <?php
    $landing->displayTopbar();
    $submenu->displaySubmenu('offer');
    $offer->displayoffer();
    $offer->displaydiscount();
    $footer->displayFooterMenu();
    
    


    ?>
</body>
</html>
