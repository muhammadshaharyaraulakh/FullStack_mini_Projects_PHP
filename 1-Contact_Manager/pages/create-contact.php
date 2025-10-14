<?php
session_start(); 

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/nav.php';

$errors = [];
if (empty($_SESSION['id'])) {
    header("Location: /auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name     = $_POST['name'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $email    = $_POST['email'] ?? '';
    $birthday = $_POST['birthday'] ?? '';
    $notes    = $_POST['notes'] ?? '';

    // Basic empty field check
    if (empty($name) || empty($phone) || empty($email) || empty($birthday) || empty($notes)) {
        $errors[] = "All fields are required.";
    }

    // ✅ Additional validations
    if (!empty($name) && !preg_match('/^[a-zA-Z ]+$/', $name)) {
        $errors[] = "Name can only contain letters (A–Z or a–z) and spaces.";
    }

    if (!empty($phone) && !preg_match('/^[0-9]{11}$/', $phone)) {
        $errors[] = "Phone number must contain exactly 11 digits (0–9 only).";
    }

    if (!empty($notes) && !preg_match('/^[a-zA-Z0-9 ]+$/', $notes)) {
        $errors[] = "Notes can only contain letters (A–Z, a–z), numbers (0–9), and spaces.";
    }

    // ✅ New birthday validation
    if (!empty($birthday)) {
        $birthdayDate = strtotime($birthday);
        $today = strtotime(date('Y-m-d'));
        if ($birthdayDate >= $today) {
            $errors[] = "Birthday must be earlier than today’s date.";
        }
    }

    if (empty($errors)) {
        $userId = $_SESSION['id'] ?? null;

        if (!$userId) {
            $errors[] = "You must be logged in to add a contact.";
        } else {
            try {
                $query = $connection->prepare("
                    INSERT INTO contacts (user_id, name, phone, email, birthday, notes)
                    VALUES (:user_id, :name, :phone, :email, :birthday, :notes)
                ");
                $query->execute([
                    ':user_id'  => $userId,
                    ':name'     => $name,
                    ':phone'    => $phone,
                    ':email'    => $email,
                    ':birthday' => $birthday,
                    ':notes'    => $notes
                ]);
                header("Location: ../index.php");
                exit;
            } catch (PDOException $e) {
                $errors[] = "Database Error: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>

<main class="container">
    <div class="form-page-container">
        <h2>Add New Contact</h2>
        <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form id="createContactForm" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" id="birthday" name="birthday" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Contact</button>
            <a href="/index.php" class="btn btn-secondary" style="margin-top: 10px; display: block; text-align: center;">Cancel</a>
        </form>
    </div>
</main>

<script src="/js/app.js"></script>
</body>
</html>