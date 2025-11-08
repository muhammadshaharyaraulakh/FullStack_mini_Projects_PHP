<?php
require_once __DIR__ . "/../../config/config.php";
header("Content-Type: application/json");

session_start();

$response = ["status" => "error", "message" => "Unexpected error occurred."];

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        // Basic validation
        if (empty($email) || empty($password)) {
            throw new Exception("Please fill in all fields.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Check if user exists by email only
        $stmt = $connection->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user) {
            throw new Exception("No account found with that email.");
        }

        // Verify password
        if (!password_verify($password, $user->password)) {
            throw new Exception("Incorrect password.");
        }

        // Start session
        $_SESSION['id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['email'] = $user->email;

        $response = ["status" => "success", "message" => "Login successful! Redirecting..."];
    }
} catch (Exception $e) {
    $response["message"] = $e->getMessage();
} catch (PDOException $e) {
    $response["message"] = "Database error occurred: " . $e->getMessage();
}

echo json_encode($response);
