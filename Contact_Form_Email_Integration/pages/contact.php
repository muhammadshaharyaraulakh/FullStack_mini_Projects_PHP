<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Composer autoload
require __DIR__ . '/../vendor/autoload.php';

session_start();
if (empty($_SESSION['id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['status'=>'error','messages'=>['Not logged in']]);
        exit;
    } else {
        header("Location: /auth/login/login.php");
        exit;
    }
}

// ---------- AJAX POST handler ----------
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['ajax'])) {
    header('Content-Type: application/json');

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];
    if (empty($name)) $errors[] = "Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($subject)) $errors[] = "Subject is required.";
    if (empty($message)) $errors[] = "Message cannot be empty.";

    if (!empty($errors)) {
        echo json_encode(['status'=>'error','messages'=>$errors]);
        exit; 
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';        // Your Gmail
        $mail->Password = '';        // Your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('', 'Name'); // Must be your Gmail and Name of App
        $mail->addReplyTo($email, $name);                             // Visitor's email
        $mail->addAddress('muhammadshaharyaraulakh@gmail.com');       // Your Gmail

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "<b>Name:</b> $name<br>
                       <b>Email:</b> $email<br>
                       <b>Message:</b><br>$message";

        $mail->send();
        echo json_encode(['status'=>'success','message'=>'Message sent successfully!']);
        exit;

    } catch (Exception $e) {
        echo json_encode(['status'=>'error','messages'=>["Mailer Error: ".$mail->ErrorInfo]]);
        exit;
    }
}
// ---------- End AJAX handler ----------

// ---------- Normal GET page (HTML) ----------
require __DIR__ . '/../includes/header.php';
?>

<section id="contact" class="contact-section">
    <div class="container">
        <div class="section-header">
            <h2>Get In <span class="highlight">Touch</span></h2>
            <p>I'm always open to discussing new projects, creative ideas, or opportunities to be part of your visions.</p>
        </div>

        <form id="contactForm" method="POST" class="contact-form">
            <input type="hidden" name="ajax" value="1">

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter a valid email address" required>
                </div>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="form-input" placeholder="What is this about?" required>
            </div>

            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea id="message" name="message" class="form-input" rows="6" placeholder="Tell me more about your project..." required></textarea>
            </div>

            <div class="form-submit">
                <button type="submit" class="btn btn-primary">Send Message</button>
            </div>
        </form>
        <div id="formResponse" style="margin-top:15px;"></div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e){
    e.preventDefault();
    let form = e.target;
    let formData = new FormData(form);
    let responseDiv = document.getElementById('formResponse');
    responseDiv.innerHTML = '>Sending Your Message Please Wait';

    fetch('<?= basename(__FILE__) ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success'){
            responseDiv.innerHTML = '<span style="color:green;">'+data.message+'</span>';
            setTimeout(() => { window.location.href = '/index.php'; }, 1500);
        } else {
            responseDiv.innerHTML = '<span style="color:red;">'+data.messages.join('<br>')+'</span>';
        }
    })
    .catch(err => {
        console.error(err);
        responseDiv.innerHTML = '<span style="color:red;">AJAX request failed. Check console for details.</span>';
    });
});
</script>

<?php
require __DIR__ . '/../includes/footer.php';
?>
