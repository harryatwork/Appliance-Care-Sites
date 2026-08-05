<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Already logged in → go to dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: leads.php');
    exit;
}

require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter your username and password.';
    } else {
        try {
            $stmt = db()->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id']   = $user['id'];
                $_SESSION['admin_user'] = $user['username'];
                header('Location: leads.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (Exception $e) {
            $error = 'Database error. Please run <a href="setup.php">setup.php</a> first.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login — We Assist</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="login-wrap">
  <div class="login-box">
    <div class="login-box__logo">
      <img src="../assets/images/logo.png" alt="We Assist" class="login-logo">
      <p>Admin Panel &mdash; Sign in to continue</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert--error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="on">
      <div class="form-group">
        <label for="username">Username</label>
        <input
          type="text" id="username" name="username"
          required autofocus autocomplete="username"
          value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
        >
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <div style="position:relative">
          <input
            type="password" id="password" name="password"
            required autocomplete="current-password"
            style="padding-right:42px"
          >
          <button type="button" id="pwToggle" aria-label="Toggle password"
            style="position:absolute;right:0;top:0;height:100%;width:40px;background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;justify-content:center;font-size:.95rem">
            <i class="fa-solid fa-eye" id="pwIcon"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="btn btn--primary">
        <i class="fa-solid fa-right-to-bracket"></i> Sign In
      </button>
    </form>
  </div>
</div>
<script>
  const pwToggle = document.getElementById('pwToggle');
  const pwInput  = document.getElementById('password');
  const pwIcon   = document.getElementById('pwIcon');
  pwToggle.addEventListener('click', () => {
    const show = pwInput.type === 'password';
    pwInput.type = show ? 'text' : 'password';
    pwIcon.className = show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
  });
</script>
</body>
</html>
