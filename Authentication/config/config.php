
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$dataBase = "Authentication";
$db_user = "root";
$db_pass = "1234";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$dataBase;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $connection = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}
?>