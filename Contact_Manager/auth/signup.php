<?php 
// Include configuration file for database connection and other settings
require_once __DIR__ . '/../config/config.php';

// Include navigation bar (shared across pages)
require_once __DIR__ . '/../includes/nav.php';

// Start session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize error array and success message variable
$errors = [];
$success = "";

// Handle form submission when the request method is POST
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Retrieve and sanitize form inputs
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Perform server-side validation

    // Check for empty fields
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    }

    // Check minimum username length
    if (strlen($username) < 6) {
        $errors[] = "Username must be at least 6 characters.";
    }

    // Allow only letters in username
    if (!preg_match('/^[a-zA-Z]+$/', $username)) {
        $errors[] = "Username can only contain letters (A–Z, a–z).";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Check minimum password length
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Ensure password and confirmation match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Continue only if there are no validation errors
    if (empty($errors)) {
        try {
            // Check if username or email already exists in the database
            $check = $connection->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $check->execute([':username' => $username, ':email' => $email]);

            // If user is found, show error
            if ($check->fetch()) {
                $errors[] = "Username or email already taken.";
            } else {
                // Hash the password for security
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user into the database
                $query = $connection->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $query->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $hashedPassword
                ]);

                // Redirect user to login page after successful signup
                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            // Catch and display database connection or query errors
            $errors[] = "Database Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<main class="container">
    <div class="form-page-container">
        <h2>Sign Up</h2>

        <!-- Display error messages if there are any -->
        <?php foreach ($errors as $err): ?>
            <p style="color:red"><?= htmlspecialchars($err) ?></p>
        <?php endforeach; ?>

        <!-- Signup form -->
        <form method="POST" action="signup.php" id="signupForm">
            <!-- Username input -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    pattern="[A-Za-z]+" 
                    title="Only letters A–Z and a–z are allowed"
                    required
                >
            </div>

            <!-- Email input -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                >
            </div>

            <!-- Password input -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Confirm password input -->
            <div class="form-group">
                <label for="confirm_password">Repeat Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Sign Up</button>

            <!-- Link to login page for existing users -->
            <p style="text-align: center; margin-top: 20px; color: var(--light-text-color);">
                Already have an account? 
                <a href="login.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Login</a>
            </p>
        </form>
    </div>
</main>

<!-- Include JavaScript file for client-side behavior -->
<script src="/js/app.js"></script>
</body>
</html>
