<?php require_once __DIR__ . "/../../includes/header.php" ?>

<section class="login-section">
  <div class="container login-container">
    <div class="login-form-card fade-in" id="loginFormCard">
      <h2>Join Our Community</h2>

      <form id="signupForm" class="login-form" novalidate>
        <div class="input-group">
          <label for="username">Username</label>
          <div class="input-field-wrapper">
            <input type="text" id="username" name="username" placeholder="Choose a unique username" required>
            <i class="fas fa-user icon"></i>
          </div>
        </div>

        <div class="input-group">
          <label for="email">Email Address</label>
          <div class="input-field-wrapper">
            <input type="email" id="email" name="email" placeholder="you@example.com" required>
            <i class="fas fa-envelope icon"></i>
          </div>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <div class="input-field-wrapper">
            <input type="password" id="password" name="password"  required>
            <i class="fas fa-lock icon"></i>
            <button type="button" class="toggle-password" id="togglePassword"><i class="far fa-eye-slash"></i></button>
          </div>
        </div>

        <div class="input-group">
          <label for="confirm_password">Confirm Password</label>
          <div class="input-field-wrapper">
            <input type="password" id="confirm_password" name="confirm_password"  required>
            <i class="fas fa-lock icon"></i>
            <button type="button" class="toggle-password" id="toggleConfirmPassword"><i class="far fa-eye-slash"></i></button>
          </div>
        </div>

        <div class="input-group" style="text-align:center;font-size:0.9rem;">
          <input type="checkbox" id="terms" name="terms" required style="width:auto;margin-right:0.5rem;">
          <label for="terms" style="display:inline;font-weight:400;">
            I agree to the <a href="#">Terms and Privacy Policy</a>
          </label>
        </div>

        <button type="submit" class="btn btn-primary login-btn">Create Account</button>
      </form>

      <div id="formMessage" style="margin-top:1rem;text-align:center;font-weight:bold;"></div>
    </div>
  </div>
</section>

<script>
document.getElementById("signupForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const messageBox = document.getElementById("formMessage");

    // Show loading message
    messageBox.innerHTML = " Creating your account...";
    messageBox.style.color = "#333";

    try {
        const response = await fetch("signup_handler.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (data.status === "success") {
            messageBox.innerHTML = data.message;
            messageBox.style.color = "green";
            form.reset();
            setTimeout(() => window.location.href = "../login/login.php", 2000);
        } else {
            // Show backend error here
            messageBox.innerHTML = data.message;
            messageBox.style.color = "red";
        }
    } catch (err) {
        messageBox.innerHTML = "An unexpected error occurred. Please try again.";
        messageBox.style.color = "red";
    }
});
</script>

<?php require_once __DIR__ . "/../../includes/footer.php" ?>
