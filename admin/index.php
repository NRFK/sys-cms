<?php

session_start();

require_once __DIR__ . '/../platform/config.php';
require_once __DIR__ . '/../platform/functions.php';

$route = $_GET['route'] ?? 'dashboard';

$adminPublicRoutes = ['login'];

if (!isset($adminPublicRoutes) || !is_array($adminPublicRoutes)) {
    $adminPublicRoutes = [];
}

if (!in_array($route, $adminPublicRoutes)) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /admin/?route=login");
        exit;
    }
}

$adminTemplateName = 'default';
renderAdmin($route, $adminTemplateName, $config);

?>