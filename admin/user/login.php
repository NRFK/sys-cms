<?php
session_start();
require_once '../../platform/database.php';

$action = $_POST['action'] ?? '';

switch ($action) {
	case 'login':
		login($conn);
		break;

	case 'register':
		register($conn);
		break;

	default:
		$_SESSION['error'] = "Invalid action.";
		header("Location: /admin/?route=login");
		exit;
}

function login($conn) {
	$username = trim($_POST['username']);
	$password = $_POST['password'];

	$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows === 1) {
		$stmt->bind_result($user_id, $hashed);
		$stmt->fetch();
		if (password_verify($password, $hashed)) {
			$_SESSION['user_id'] = $user_id;
			$_SESSION['username'] = $username;
			header("Location: /admin");
			exit;
		}
	}

	$_SESSION['error'] = "Invalid username or password.";
	header("Location: /admin/?route=login");
	exit;
}

function register($conn) {
	$username = trim($_POST['username']);
	$password = $_POST['password'];

	if (strlen($username) < 3 || strlen($password) < 6) {
		$_SESSION['error'] = "Username must be at least 3 chars, password 6.";
		header("Location: /admin/?route=register");
		exit;
	}

	$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows > 0) {
		$_SESSION['error'] = "Username already taken.";
		header("Location: /admin/?route=register");
		exit;
	}

	$hash = password_hash($password, PASSWORD_DEFAULT);
	$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
	$stmt->bind_param("ss", $username, $hash);

	if ($stmt->execute()) {
		$_SESSION['success'] = "Registration successful. You can now log in.";
		header("Location: /admin/?route=dashboard&sec=useradded");
	} else {
		$_SESSION['error'] = "Something went wrong. Try again.";
		header("Location: /admin/?route=dashboard&sec=usererror");
	}
	exit;
}
