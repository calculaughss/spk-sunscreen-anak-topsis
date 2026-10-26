<?php
/**
 * generate_admin.php
 * Jalankan file ini sekali via browser untuk membuat akun admin default
 * (username: admin, password: admin123), lalu HAPUS file ini setelah selesai.
 */
require_once 'includes/config.php';

$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$cek = $koneksi->query("SELECT id FROM admin WHERE username='$username'");
if ($cek->num_rows > 0) {
    $koneksi->query("UPDATE admin SET password='$hash' WHERE username='$username'");
    echo "Password admin berhasil di-reset.<br>";
} else {
    $stmt = $koneksi->prepare("INSERT INTO admin (username, password, nama) VALUES (?,?,?)");
    $nama = 'Administrator';
    $stmt->bind_param('sss', $username, $hash, $nama);
    $stmt->execute();
    echo "Akun admin berhasil dibuat.<br>";
}

echo "Username: <b>admin</b><br>Password: <b>admin123</b><br><br>";
echo "<strong>Harap hapus file generate_admin.php ini setelah digunakan.</strong>";
