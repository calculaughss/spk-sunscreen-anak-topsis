<?php
/**
 * auth_check.php
 * Memastikan session admin aktif.
 * Sisi pengguna (user) tidak lagi menggunakan sistem login/akun -
 * SPK ini didesain sederhana: siapa saja bisa langsung konsultasi
 * tanpa harus membuat akun terlebih dahulu.
 * Gunakan: require_once '../includes/auth_check.php';
 * Lalu panggil cekLoginAdmin() di halaman admin terkait.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cekLoginAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL . 'admin/login.php');
        exit;
    }
}
