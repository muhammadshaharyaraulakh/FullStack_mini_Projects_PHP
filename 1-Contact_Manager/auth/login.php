<?php 
// Load configuration settings and database connection
require_once __DIR__ . '/../config/config.php';

// Include the navigation bar
require_once __DIR__ .'/../includes/nav.php';

// Start a session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize an array to hold error messages
$errors = [];

// Check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Retrieve and sanitize user input
    $user = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if username or password is empty
    if (empty($user) || empty($password)) {
        $errors[] = "All fields are required.";
    }

    // Check if username meets minimum length requirement
    if (strlen($user) < 6) {
        $errors[] = "Username must be at least 6 characters.";
    }

    // Validate username to contain only letters
    if (!preg_match('/^[a-zA-Z]+$/', $user)) {
        $errors[] = "Username can only contain letters (A–Z, a–z).";
    }

    // Check if password meets minimum length requirement
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Proceed with login only if there are no validation errors
    if (empty($errors)) {
        try {
            // Prepare and execute query to find user by username
            $query = $connection->prepare("SELECT * FROM users WHERE username = :username");
            $query->execute([':username' => $user]);

            // Fetch the user as an object
            $Result = $query->fetch(PDO::FETCH_OBJ);

            // Verify the password and log the user in if valid
            if ($Result && password_verify($password, $Result->password)) {
                // Store user information in session
                $_SESSION['id'] = $Result->id;
                $_SESSION['username'] = $Result->username;

                // Redirect to homepage after successful login
                header("Location: ../index.php");
                exit;
            } else {
                // Add error if login credentials are incorrect
                $errors[] = "Invalid username or password.";
            }

        } catch (PDOException $e) {
            // Display database error message
            echo "Database Error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>


    <main class="container" >
        <div class="form-page-container">
            <h2>Login</h2>

            <?php 
            // Display error messages, if any
            foreach ($errors as $err) {
            echo "<p style='color:red'>" . htmlspecialchars($err) . "</p>";
           }
            ?>

            <!-- Login form -->
            <form  method="POST" action="login.php" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>

                <!-- Link to signup page -->
                <p style="text-align: center; margin-top: 20px; color: var(--light-text-color);">
                    Don't have an account? <a href="signup.php" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Sign Up</a>
                </p>
            </form>
        </div>
    </main>

    <!-- Link to external JavaScript file -->
    <script src="/js/app.js"></script>
    
</body>
</html>
