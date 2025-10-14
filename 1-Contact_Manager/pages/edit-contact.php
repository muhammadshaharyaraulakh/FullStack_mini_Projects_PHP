<?php
session_start(); 
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/nav.php';

$errors = [];
$contact = null;

if (empty($_SESSION['id'])) {
    header("Location: /auth/login.php");
    exit;
}

$userId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Handle form submission
    $contactId = $_POST['id'] ?? null;
    $name      = $_POST['name'] ?? '';
    $phone     = $_POST['phone'] ?? '';
    $email     = $_POST['email'] ?? '';
    $birthday  = $_POST['birthday'] ?? '';
    $notes     = $_POST['notes'] ?? '';

    // ✅ Basic field check
    if (empty($contactId) || empty($name) || empty($phone) || empty($email) || empty($birthday) || empty($notes)) {
        $errors[] = "All fields are required.";
    }

    // ✅ Custom validations
    if (!empty($name) && !preg_match('/^[a-zA-Z ]+$/', $name)) {
        $errors[] = "Name can only contain letters (A–Z or a–z) and spaces.";
    }

    if (!empty($phone) && !preg_match('/^[0-9]{11}$/', $phone)) {
        $errors[] = "Phone number must contain exactly 11 digits (0–9 only).";
    }

    if (!empty($notes) && !preg_match('/^[a-zA-Z0-9 ]+$/', $notes)) {
        $errors[] = "Notes can only contain letters (A–Z, a–z), numbers (0–9), and spaces.";
    }

    // ✅ Birthday validation (must be before today)
    if (!empty($birthday)) {
        $birthdayDate = strtotime($birthday);
        $today = strtotime(date('Y-m-d'));
        if ($birthdayDate >= $today) {
            $errors[] = "Birthday must be earlier than today’s date.";
        }
    }

    // ✅ If no validation errors, update contact
    if (empty($errors)) {
        try {
            $query = $connection->prepare("
                UPDATE contacts
                SET name = :name,
                    phone = :phone,
                    email = :email,
                    birthday = :birthday,
                    notes = :notes
                WHERE id = :id AND user_id = :user_id
            ");
            $query->execute([
                ':id'       => $contactId,
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
    } else {
        // Repopulate $contact for redisplay
        $contact = (object)[
            'id'       => $contactId,
            'name'     => $name,
            'phone'    => $phone,
            'email'    => $email,
            'birthday' => $birthday,
            'notes'    => $notes
        ];
    }
} elseif (isset($_GET['id'])) {
    // Handle initial form load
    $contactId = $_GET['id'];
    try {
        $query = $connection->prepare("SELECT * FROM contacts WHERE id = :contact_ID AND user_id = :user_id");
        $query->execute([
            ':contact_ID' => $contactId,
            ':user_id' => $userId
        ]);
        $contact = $query->fetch(PDO::FETCH_OBJ);

        if (!$contact) {
            $errors[] = "Contact not found or access denied.";
        }

    } catch (PDOException $e) {
        $errors[] = "Database connection error: " . htmlspecialchars($e->getMessage());
    }
} else {
    $errors[] = "No contact ID provided.";
}
?>

<main class="container">
    <div class="form-page-container">
        <h2>Edit Contact</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li style="color:red;"><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($contact): ?>
            <form id="editContactForm" action="" method="POST"> 
                <input type="hidden" name="id" value="<?= htmlspecialchars($contact->id) ?>">
                
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($contact->name) ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($contact->phone) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($contact->email) ?>" required>
                </div>

                <div class="form-group">
                    <label for="birthday">Birthday:</label>
                    <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($contact->birthday) ?>" required>
                </div>

                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea id="notes" name="notes" rows="3" required><?= htmlspecialchars($contact->notes) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Contact</button>
                <a href="/index.php" class="btn btn-secondary" style="margin-top: 10px; display: block; text-align: center;">Cancel</a>
            </form>
        <?php endif; ?>
    </div>
</main>

<script src="/js/app.js"></script>
</body>
</html>