<?php
/**
 * Konfigurasi Koneksi Database
 * SPK Pemilihan Sunscreen Anak Berdasarkan Jenis Kulit
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'spk_sunscreen2');

// Koneksi mysqli
$koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($koneksi->connect_error) {
    die('Koneksi database gagal: ' . $koneksi->connect_error);
}

$koneksi->set_charset('utf8mb4');

// Base URL aplikasi (sesuaikan dengan folder instalasi)
define('BASE_URL', '/sun/');
