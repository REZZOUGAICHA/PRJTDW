<?php

require_once 'FileUploadHelper.php';

if (isset($_GET['user_id'])) {
    $helper = new FileUploadHelper();
    $helper->displayImage($_GET['user_id']);
}
?>