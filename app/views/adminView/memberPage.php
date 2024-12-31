<?php

require_once 'sidebarView.php';
require_once 'partnerView.php';
require_once 'EntityView.php';

$sidebar = new SidebarView();
$partnerView = new PartnerView();
$entityView = new EntityView();
$link = '/entity/partner/1-c44e5c1be8f8';  // Example link for the partner
$tableName = 'partner';

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
<body class="bg-gray-100 overflow-x-hidden">
    <div class="flex min-h-screen">
        <!-- Sidebar Section -->
        <div class="w-64 bg-white  shadow-lg h-full">
            <?php $sidebar->displaySidebar(); ?>
        </div>

        <!-- members Section -->
        <div class="flex-1 bg-white p-6 shadow-lg overflow-y-auto">
            <?php $entityView->displayEntity($link, $tableName); ?>
        </div>
    </div>
</body>
</html>
