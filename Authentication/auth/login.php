 <?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/header.php";



$user_error = $email_error = $password_error = $error = $db_error = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Please enter a valid email address.";
    }

    // Validate username
    if (!preg_match('/^[a-zA-Z0-9_]{6,20}$/', $username)) {
        $user_error = "Username must be 6–20 characters long and contain only letters, numbers, or underscores.";
    }

    // Validate password length
    if (strlen($password) < 6) {
        $password_error = "Password must be at least 6 characters long.";
    }

    // Proceed if no validation errors
    if (empty($password_error) && empty($email_error) && empty($user_error)) {
        try {
            $stmt = $connection->prepare("SELECT * FROM user WHERE username = :username OR email = :email LIMIT 1");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email
            ]);

            $user = $stmt->fetch();

            if (!$user) {
                $error = "No account found with that username or email.";
            } else {
                // Verify password
                if (password_verify($password, $user->password)) {
                    // Create session
                    $_SESSION['id'] = $user->id;
                    $_SESSION['username'] = $user->username;
                    $_SESSION['email'] = $user->email;


                    header("Location: /index.php");
                    exit;
                } else {
                    $password_error = "Incorrect password.";
                }
            }
        } catch (PDOException $e) {
            $db_error = "Database Error.";
        }
    }
}
?>

<main class="login-container">
    <div class="login-card animate-on-scroll">
        <h2 class="login-title">Welcome Back</h2>

        <?php if (!empty($error) || !empty($db_error)): ?>
            <div class="form-error"><?= htmlspecialchars($error ?: $db_error) ?></div>
        <?php endif; ?>

        <form class="login-form" method="post" action="login.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            <p class="form-error"><?= htmlspecialchars($user_error) ?></p>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            <p class="form-error"><?= htmlspecialchars($email_error) ?></p>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <p class="form-error"><?= htmlspecialchars($password_error) ?></p>

            <button type="submit" class="cta-button primary-cta login-btn">Login</button>

            <p class="signup-text">
                Don't have an account? <a href="signup.php" class="text-link">Sign Up Here</a>
            </p>
        </form>
    </div>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?> 