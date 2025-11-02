<?php 
require_once __DIR__ . '/../config/config.php';

if (session_status()===PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Contacts Manager</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header class="app-header">
        <div class="container nav-container">
            <h1 class="nav-heading"><i class="fas fa-address-book"></i> Your Contacts</h1>
            <nav class="main-nav" id="mainNav">
    <a href="/index.php" class="nav-link active">Home</a>

    <?php if (!empty($_SESSION['id'])): ?>
        <a href="#" class="nav-link"><?= htmlspecialchars($_SESSION['username']) ?></a>
        <a href="/pages/create-contact.php" class="nav-link">Create</a>
        <a href="/auth/logout.php" class="nav-link">Logout</a>
    <?php else: ?>
        <a href="/auth/login.php" class="nav-link">Login</a>
        <a href="/auth/signup.php" class="nav-link">Sign Up</a>
    <?php endif; ?>
</nav>

            <button class="hamburger-menu" id="hamburgerMenu" aria-label="Toggle navigation">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>
    </header>