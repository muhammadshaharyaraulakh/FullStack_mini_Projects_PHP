<?php
session_start();

// Include configuration and navigation bar files
require_once "./config/config.php";
require_once "./includes/nav.php";

// Get the current logged-in user ID from the session
$userId = $_SESSION['id'] ?? null;

// Redirect to login page if user is not authenticated
if (!$userId) {
    header("Location: ./login.php");
    exit;
}

// Initialize error and success message containers
$errors = [];
$success = "";

// Handle form submission for deleting a contact
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $contactId = $_POST['contact_id'] ?? null;

    // Validate that contact ID and user session exist
    if ($contactId && $userId) {
        try {
            // Prepare SQL statement to delete contact belonging to the logged-in user
            $deleteQuery = $connection->prepare("DELETE FROM contacts WHERE id = :id AND user_id = :user_id");
            $deleteQuery->execute([
                ':id' => $contactId,
                ':user_id' => $userId
            ]);

            // Check if any row was affected (i.e., contact was deleted)
            if ($deleteQuery->rowCount() > 0) {
                $success = "Contact deleted successfully.";
                // Redirect to main page after successful deletion to prevent form resubmission
                header("Location: index.php");
                exit;
            } else {
                // No rows deleted means either contact does not exist or does not belong to user
                $errors[] = "No contact deleted. It may not exist or doesn't belong to you.";
            }
        } catch (PDOException $e) {
            // Catch and log database errors securely
            $errors[] = "Delete Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        // Handle missing contact ID or invalid session
        $errors[] = "Missing contact ID or session.";
    }
}

// Fetch all contacts belonging to the current user from the database
try {
    $query = $connection->prepare("SELECT * FROM contacts WHERE user_id = :user_id");
    $query->execute([':user_id' => $userId]);
    $contacts = $query->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    // Display a user-friendly message if database query fails
    echo "Database error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<main class="container">
    <!-- Display any error messages -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Display success message -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <p><?= htmlspecialchars($success) ?></p>
        </div>
    <?php endif; ?>

    <!-- Search input for filtering contacts -->
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search contacts" class="search-input">
        <i class="fas fa-search search-icon"></i>
    </div>

    <!-- List all contacts -->
    <div class="contacts-list" id="contactsList">
        <?php if (!empty($contacts)): ?>
            <?php foreach ($contacts as $contact): ?>
                <div class="contact-card" data-id="<?= htmlspecialchars($contact->id) ?>">
                    <div class="contact-header">
                        <!-- Display contact initial as avatar -->
                        <div class="contact-avatar"><?= strtoupper(htmlspecialchars($contact->name[0])) ?></div>
                        <div class="contact-info">
                            <h3><?= htmlspecialchars($contact->name) ?></h3>
                        </div>
                    </div>
                    <div class="contact-details">
                        <p><i class="fas fa-phone"></i> <?= htmlspecialchars($contact->phone) ?></p>
                        <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($contact->email) ?></p>
                        <p><i class="fas fa-birthday-cake"></i> <?= htmlspecialchars($contact->birthday) ?></p>
                        <p><i class="fas fa-sticky-note"></i> <?= htmlspecialchars($contact->notes) ?></p>
                    </div>
                    <div class="contact-actions">
                        <!-- Edit contact button -->
                        <a href="/pages/edit-contact.php?id=<?= htmlspecialchars($contact->id) ?>" class="btn btn-primary action-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <!-- Delete contact form -->
                        <form method="POST" action="" id="Delete-Form">
                            <input type="hidden" name="contact_id" value="<?= htmlspecialchars($contact->id) ?>">
                            <button type="submit" class="btn btn-danger action-btn">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Display message if no contacts found -->
            <p class="no-contacts-message">No contacts found. Add some!</p>
        <?php endif; ?>
    </div>
</main>

<!-- Link to the JavaScript file for client-side functionality -->
<script src="/js/app.js"></script>
</body>
</html>
