<?php require_once __DIR__ . '/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creativity Freaks</title>

   
  <link rel="icon" type="image/png" href="/creativityfreaks/assets/images/favicon.png">


  <!-- AOS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script defer src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      AOS.init();
    });
  </script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Inter', sans-serif;
  }
</style>

  <!-- Fonts & Style -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/creativityfreaks/assets/css/style.css"> 
  <?php echo csrf_meta_tag(); ?>
</head>
<body>

<?php include 'header.php'; ?>

<main class="container">
  <?php
    if (isset($content) && file_exists($content)) {
      include $content;
    } else {
      include __DIR__ . '/../pages/home.php';
    }
  ?>
</main>


 
<?php include 'footer.php'; ?>


  <script defer src="/creativityfreaks/assets/js/main.js"></script>

  <!-- Auth Modal -->
<div id="authModal" class="auth-modal hidden">
  <div class="auth-content" id="authContent">
    
    <span class="close-btn" onclick="toggleModal()">&times;</span>
    
    <!-- Toggle Buttons -->
    <div class="auth-tabs">
      <button onclick="showForm('login')">Login</button>
      <button onclick="showForm('register')">Register</button>
    </div>

    <!-- Login Form -->
    <form id="loginForm" action="/creativityfreaks/auth/login.php" method="POST" class="auth-form">
      <h2>Login</h2>
      <?php if (isset($_SESSION['error']) && $_SESSION['error_type'] === 'login'): ?>
  <div class="auth-error"><?php echo $_SESSION['error']; unset($_SESSION['error'], $_SESSION['error_type']); ?></div>
<?php endif; ?>

      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />
      <button type="submit">Login</button>
      <p class="link-text"><a href="#">Forgot Password?</a></p>
    </form>

    <!-- Register Form -->
    <form id="registerForm" action="/creativityfreaks/auth/register.php" method="POST" class="auth-form hidden">
      <h2>Register</h2>
      <?php if (isset($_SESSION['error']) && $_SESSION['error_type'] === 'register'): ?>
  <div class="auth-error"><?php echo $_SESSION['error']; unset($_SESSION['error'], $_SESSION['error_type']); ?></div>
<?php endif; ?>

      <input type="text" name="name" placeholder="Full Name" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <input type="password" name="confirm_password" placeholder="Confirm Password" required />
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" />
      <button type="submit">Register</button>
    </form>

  </div>
</div>

</body>
</html>
