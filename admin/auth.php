<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header('Location: login.php');
    exit;
}

$sessionTimeout = 1800; // 30 minutes

if (isset($_SESSION['last_activity'])) { 
    $inactiveTime = time() - $_SESSION['last_activity'];
    if ($inactiveTime >= $sessionTimeout) {     
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }
}
 
$_SESSION['last_activity'] = time();
?>
