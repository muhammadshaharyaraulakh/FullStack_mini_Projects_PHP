<?php 
require __DIR__ . "/../../includes/header.php"; 
?>

<section class="login-section">
  <div class="container login-container">
    <div class="login-form-card fade-in" id="loginFormCard">
      <h2>Welcome Back!</h2>

      <form id="loginForm" class="login-form" novalidate>
        <div class="input-group">
          <label for="email">Email </label>
          <div class="input-field-wrapper">
            <input type="text" id="email" name="email" placeholder="Enter your email or username" required>
            <i class="fas fa-user icon"></i>
          </div>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <div class="input-field-wrapper">
            <input type="password" id="password" name="password"  required>
            <i class="fas fa-lock icon"></i>
            <button type="button" class="toggle-password" id="togglePassword">
              <i class="far fa-eye-slash"></i>
            </button>
          </div>
        </div>

        </div>

        <button type="submit" class="btn btn-primary login-btn">Log In Securely</button>
      </form>

      <div id="formMessage" style="margin-top:1rem;text-align:center;font-weight:bold;"></div>

      <div class="signup-link">
        <p>Don't have an account? <a href="../signup/signup.php">Sign Up</a></p>
      </div>
    </div>
  </div>
</section>

<script>
document.getElementById("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);
  const messageBox = document.getElementById("formMessage");

  messageBox.innerHTML = "Logging you In";
  messageBox.style.color = "#333";

  try {
    const response = await fetch("handler.php", {
      method: "POST",
      body: formData
    });

    const data = await response.json();

    if (data.status === "success") {
      messageBox.innerHTML = data.message;
      messageBox.style.color = "green";
      setTimeout(() => window.location.href = "/index.php",1000);
    } else {
      messageBox.innerHTML = data.message;
      messageBox.style.color = "red";
    }
  } catch (err) {
    messageBox.innerHTML = " An unexpected error occurred. Please try again.";
    messageBox.style.color = "red";
  }
});
</script>

<?php require __DIR__ . "/../../includes/footer.php"; ?>
