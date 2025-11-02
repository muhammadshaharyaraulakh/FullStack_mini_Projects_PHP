<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/header.php";


$user_error = $email_error = $password_error = $error = $db_error = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Please enter a valid email address.";
    }


    if (!preg_match('/^[a-zA-Z0-9_]{6,20}$/', $username)) {
        $user_error = "Username must be 6–20 characters long and contain only letters, numbers, or underscores.";
    }

    if ($password !== $confirm_password) {
        $password_error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $password_error = "Password must be at least 6 characters long.";
    }


    if (empty($password_error) && empty($email_error) && empty($user_error)) {
        try {
            $Check = $connection->prepare("SELECT * FROM user WHERE username = :username OR email = :email");
            $Check->execute([
                ':username' => $username,
                ':email' => $email
            ]);

            if ($Check->fetch()) {
                $error = "Username or email already exists!";
            } else {
                $hashed_Password = password_hash($password, PASSWORD_DEFAULT);
                $Query = $connection->prepare("INSERT INTO user (username, email, password) VALUES (:username, :email, :password)");
                $Query->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $hashed_Password
                ]);
                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            $db_error = "Database Error.";
        }
    }
}
?>

<main class="login-container">
    <div class="login-card animate-on-scroll">
        <h2 class="login-title">Create Your Account</h2>
        <?php if (!empty($error) || !empty($db_error)): ?>
            <div class="form-error"><?= htmlspecialchars($error ?: $db_error) ?></div>
        <?php endif; ?>
        
        <form class="login-form" method="post" action="signup.php">
            <label for="reg-email">Email Address</label>
            <input type="email" id="reg-email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            <p class="form-error"><?= htmlspecialchars($email_error) ?></p>

            <label for="reg-username">Username</label>
            <input type="text" id="reg-username" name="username" placeholder="Choose a username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            <p class="form-error"><?= htmlspecialchars($user_error) ?></p>

            <label for="reg-password">Password</label>
            <input type="password" id="reg-password" name="password" placeholder="Create a password" required>
            <p class="form-error"><?= htmlspecialchars($password_error) ?></p>

            <label for="reg-confirm-password">Confirm Password</label>
            <input type="password" id="reg-confirm-password" name="confirm-password" placeholder="Re-enter your password" required>

            <button type="submit" class="cta-button primary-cta login-btn">Sign Up</button>
            
            <p class="signup-text">
                Already have an account? <a href="login.php" class="text-link">Login Here</a>
            </p>
        </form>
    </div>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>