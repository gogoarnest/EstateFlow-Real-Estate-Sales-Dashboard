<?php
session_start();

/* لو المستخدم مسجل دخول بالفعل */
if (isset($_SESSION['user_name'])) {
    header("Location: dashboard.php");
    exit();
}

$error_message = "";

if (isset($_GET['error'])) {
    $error_message = "Invalid email or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EstateFlow | Login</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap"
    rel="stylesheet"
  />

  <link rel="stylesheet" href="css/global.css" />
  <link rel="stylesheet" href="css/login.css" />
</head>

<body>
  <div class="login-page">
    <div class="login-wrapper">

      <div class="login-left">
        <div class="brand-box">
          <h1>EstateFlow</h1>
          <p>Real Estate Sales Dashboard</p>
        </div>

        <div class="login-text">
          <h2>Manage your real estate workflow with speed and clarity.</h2>
          <p>
            Search units, update listings, compare properties, and help your
            team respond to clients faster.
          </p>
        </div>
      </div>

      <div class="login-right">
        <div class="login-card">

          <div class="card-header">
            <h2>Login</h2>
            <p>Access your account securely</p>
          </div>

          <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
          <?php endif; ?>

          <form class="login-form" action="php/auth/login.php" method="POST">
            <div class="input-group">
              <label for="email">Email</label>
              <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                required
              />
            </div>

            <div class="input-group">
              <label for="password">Password</label>
              <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
              />
            </div>

            <button type="submit" class="login-btn">Login</button>

            <p class="login-note">
              Access to this system is managed by the administrator.<br>
              Please contact your system administrator if you do not have login credentials.
            </p>
          </form>

        </div>
      </div>

    </div>
  </div>
</body>
</html>