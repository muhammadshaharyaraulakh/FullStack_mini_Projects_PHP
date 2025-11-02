<?php
session_start();

$_SESSION = [];
session_unset();

// Destroy the session
session_destroy();

// Clear the session cookie (optional but good practice)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to homepage
header("Location: /index.php");
exit;