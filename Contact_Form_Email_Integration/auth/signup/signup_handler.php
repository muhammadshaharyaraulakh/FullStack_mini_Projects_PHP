
<?php
require_once __DIR__ . "/../../config/config.php";
header("Content-Type: application/json");

$response = ["status" => "error", "message" => "Unexpected error occurred."];

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST["email"] ?? '');
        $username = trim($_POST["username"] ?? '');
        $password = $_POST["password"] ?? '';
        $confirm = $_POST["confirm_password"] ?? '';
        $terms = $_POST["terms"] ?? '';

        if (!$terms) {
            throw new Exception("You must agree to the Terms and Privacy Policy.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if (!preg_match('/^[a-zA-Z0-9_]{6,20}$/', $username)) {
            throw new Exception("Username must be 6–20 characters and contain only letters, numbers, or underscores.");
        }

        if (strlen($password) < 6 || 
            !preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/[0-9]/', $password)) {
            throw new Exception("Password must be at least 6 characters long and include an uppercase letter, lowercase letter, and number.");
        }

        if ($password !== $confirm) {
            throw new Exception("Passwords do not match.");
        }

        $check = $connection->prepare("SELECT * FROM user WHERE username = :username OR email = :email");
        $check->execute([':username' => $username, ':email' => $email]);
        if ($check->fetch()) {
            throw new Exception("Username or email already exists.");
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $insert = $connection->prepare("INSERT INTO user (username, email, password) VALUES (:username, :email, :password)");
        $insert->execute([':username' => $username, ':email' => $email, ':password' => $hashed]);

        $response = ["status" => "success", "message" => "Account created successfully!"];
    }
} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

echo json_encode($response);
