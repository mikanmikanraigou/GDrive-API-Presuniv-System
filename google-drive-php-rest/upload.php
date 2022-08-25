<?php
include_once 'config.php';
    if (isset($_POST['submit'])) {
        # hitting address to get drive oauth code, redirected to
        header("Location: $GDrive_oauth_URL");
        exit();
    }
?>