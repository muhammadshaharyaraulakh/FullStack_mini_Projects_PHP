<?php
require_once __DIR__ . "/../config/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kinetic</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/assests/css/style.css">
</head>

<body>
    <header class="main-header">
        <div class="header-content container">
            <a href="/index.php" class="logo">KINETIC</a>
            <nav class="main-nav">
                <ul>
                    <li><a class="navbar-link" href="/index.php">Home</a></li>
                    <li><a class="navbar-link" href="#">Shop</a></li>
                    <li><a class="navbar-link" href="#">Categories</a></li>
                    <?php if (empty($_SESSION['username'])): ?>
                        <li><a class="navbar-link" href="/auth/login.php">Login</a></li>
                        <li><a class="navbar-link" href="/auth/signup.php">Sign Up</a></li>
                    <?php else: ?>
                        <li><a class="navbar-link" href="#">About Us</a></li>
                        <li><a class="navbar-link" href="#">Contact Us</a></li>
                    <?php endif; ?>
                </ul>

            </nav>

            <div class="header-icons">
                <div class="user-auth-area">
                    <button class="icon-btn account-btn" id="account-dropdown-btn">
                        <i class="fas fa-user-circle"></i>
                    </button>
                    <?php if (!empty($_SESSION['username'])): ?>
                        <div class="account-dropdown" id="account-dropdown">
                            <a href="#">Profile</a>
                            <a href="#">Settings</a>
                            <a href="#">Order History</a>
                            <a href="/auth/logout.php">Logout</a>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="#" class="icon-btn cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>

                <button class="icon-btn hamburger-btn" aria-label="Toggle Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <nav class="mobile-nav-overlay" id="mobile-menu">
            <div class="mobile-menu-header">
                <a href="/index.php" class="logo mobile-logo">KINETIC</a>
                <button class="icon-btn close-btn" aria-label="Close Menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mobile-menu-links">
                <a href="#">Shop</a>
                <a href="#">Categories</a>
                <a href="#">About Us</a>
                <?php if (empty($_SESSION['username'])): ?>
                    <a href="/auth/login.php">Login</a>
                    <a href="/auth/signup.php">Sign Up</a>
                <?php else: ?>
                    <a href="#">Profile</a>
                    <a href="#">Settings</a>
                    <a href="#">Order History</a>
                    <a href="/auth/logout.php">Logout</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>