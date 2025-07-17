<?php
session_start();
require '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Auth success
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: /user/dashboard.php");
            exit();
        }
    }

    // Auth failed
    $_SESSION['error'] = "Invalid credentials.";
    header("Location: /user/login.php");
    exit();
}
?>
