<?php
require __DIR__ . "/../config/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Student Survey System | Aesthetic Design</title>
    <link rel="stylesheet" href="/assests/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <header class="navbar">
        <div class="container navbar-content">
            <a href="/index.php" class="logo">Survey<span>Program</span></a>
            <nav>
                <ul class="nav-links">
                    <li class="nav-item"><a href="/index.php">Home</a></li>
                    <li class="nav-item"><a href="/pages/about.php">About</a></li>
                    <li class="nav-item"><a href="/pages/survey.php">Survey</a></li>
                    <li class="nav-item"><a href="/pages/contact.php">Contact</a></li>
                    <?php if (empty($_SESSION['id'])): ?>
                        <li class="auth-links">
                            <a href="/auth/login/login.php" class="btn btn-secondary">Login</a>
                            <a href="/auth/signup/signup.php" class="btn btn-primary">Sign Up</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item user-menu">
                            <span class="user-icon" title="Account"><i class="fas fa-user-circle"></i></span>
                            <div class="dropdown-content">
                                <p>Welcome Back</p>
                                <a href="/index.php">
                                    <a href="/index.php">
                                        <?= !empty($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Name'; ?>
                                    </a>
                                </a>
                                <a href="/auth/logout.php"></i> Logout</a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="hamburger"><i class="fas fa-bars"></i></div>
        </div>
    </header>

    <div class="offcanvas-menu">
        <div class="offcanvas-header">
            <a href="/index.php" class="logo">

                Survey<span>Program</span></a>

            <button class="offcanvas-close"><i class="fas fa-times"></i></button>
        </div>
        <ul class="offcanvas-nav-links">
            <li><a href="/index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="/pages/about.php"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="/pages/survey.php"><i class="fas fa-poll"></i> Start Survey</a></li>
            <li><a href="/pages/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
            <?php if (!empty($_SESSION['id'])): ?>
                <li><a href="/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php endif; ?>
        </ul>
        <?php if (empty($_SESSION['id'])): ?>
            <div class="offcanvas-auth">
                <a href="/auth/login/login.php" class="btn btn-secondary">Login</a>
                <a href="/auth/signup/signup.php" class="btn btn-primary">Sign Up</a>
            </div>
        <?php endif; ?>
    </div>