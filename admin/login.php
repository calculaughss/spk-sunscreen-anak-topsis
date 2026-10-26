<?php
require_once '../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $koneksi->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nama'] = $admin['nama'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}

$pageTitle = 'Login Admin';
include '../includes/header.php';
?>
<div class="auth-wrapper">
  <div class="auth-form-side" style="background: var(--gradient-sun); flex:1;">
    <div class="auth-card">
      <div class="logo" style="color:var(--deep-blue); margin-bottom:8px; font-size:26px;">SSKids-DSS Admin</div>
      <h2>Login Administrator</h2>
      <p class="sub">Khusus untuk pengelola sistem SPK Sunscreen Anak.</p>

      <?php if ($error): ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>

      <form method="POST">
          <div class="form-group">
              <label>Username</label>
              <div class="input-icon">
                  <span><i class="bi bi-person"></i></span>
                  <input type="text" name="username" class="form-control" required autofocus>
              </div>
          </div>

          <div class="form-group">
              <label>Password</label>
              <div class="input-icon">
                  <span><i class="bi bi-lock"></i></span>
                  <input type="password" name="password" class="form-control" required>
              </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">
              Masuk sebagai Admin
          </button>
      </form>
      <div class="auth-switch"><a href="../index.php">⬅ Kembali ke Beranda</a></div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
