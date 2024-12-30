<?php

require_once 'sidebarView.php';
require_once 'partnerView.php';

$sidebar = new SidebarView();
$partnerView = new PartnerView();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../../../public/css/output.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="../../../public/js/filterTable.js"></script>


</head>
<body >
    <div class="flex">
        <!-- Sidebar Section -->
        <div >
            <?php $sidebar->displaySidebar(); ?>
        </div>

        <!-- Partners Section -->
        <div >
            <?php $partnerView->displayPartners(); ?>
        </div>
    </div>
</body>
</html>
