<?php
// Start session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define the base URL of the application (can be used for redirects, asset paths, etc.)
define("Base_URL", "/");

// Database connection configuration
$host = "localhost";               // Database host
$dataBase_name = "Contacts";       // Name of the database
$user_name = "root";               // Database username
$password = "1234";                // Database password
$charset = "utf8mb4";              // Character set for proper encoding

// Data Source Name (DSN) specifies the host, database name, and charset
$dsn = "mysql:host=$host;dbname=$dataBase_name;charset=$charset";

// PDO options for error handling and performance
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,         // Throw exceptions on database errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,      // Fetch results as objects
    PDO::ATTR_EMULATE_PREPARES => false                  // Use native prepared statements
];

// Attempt to create a PDO connection
try {
    $connection = new PDO($dsn, $user_name, $password, $options);
} catch (PDOException $e) {
    // Handle connection errors securely and gracefully
    die("Connection Failed! " . htmlspecialchars($e->getMessage()));
}
?>
