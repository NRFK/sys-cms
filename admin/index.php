<?php

session_start();

require_once __DIR__ . '/../platform/config.php';    // This loads $config AND $publicRoutes
require_once __DIR__ . '/../platform/functions.php'; // Contains renderAdmin

$route = $_GET['route'] ?? 'dashboard';

// Ensure $publicRoutes is defined and is an array (safety check, as it comes from config.php)
if (!isset($publicRoutes) || !is_array($publicRoutes)) {
    // This should ideally not happen if config.php is set up correctly.
    // Log an error or default to an empty array to prevent fatal errors.
    $publicRoutes = [];
    error_log("[CMS Error] \$publicRoutes not properly defined in config.php");
}

// --- CHANGE HERE: Use the global $publicRoutes array ---
// If the current route is NOT in the $publicRoutes list (meaning it requires login),
// AND the user is not logged in via session, then redirect to login.
if (!in_array($route, $publicRoutes)) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /admin/?route=login"); // Redirect to the admin login page
        exit;
    }
}

$adminTemplateName = 'default';

renderAdmin($route, $adminTemplateName, $config);

?>
