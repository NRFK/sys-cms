<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
	header("Location: /admin/?route=login");
	exit;
}
?>
