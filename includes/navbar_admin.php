<?php
// Tentukan menu aktif berdasarkan nama file saat ini
$current = basename($_SERVER['PHP_SELF']);
function activeMenu($file, $current) {
    return $file === $current ? 'active' : '';
}
?>
<aside class="sidebar" id="sidebar">
  <div class="logo">
    <span class="logo-icon"><i class="ti ti-sun"></i></span>
    <div class="logo-text">
      <span class="logo-title">SSKids - DSS</span>
      <span class="logo-subtitle">SunScreen Kids<br>Decision Support System</span>
    </div>
  </div>
  <ul class="sidebar-menu">
    <li><a href="dashboard.php" class="<?= activeMenu('dashboard.php', $current) ?>"><span class="icon"><i class="ti ti-layout-dashboard"></i></span> Dashboard</a></li>
    <li><a href="sunscreen.php" class="<?= activeMenu('sunscreen.php', $current) ?>"><span class="icon"><i class="ti ti-bottle"></i></span> Data Sunscreen</a></li>
    <li><a href="kriteria.php" class="<?= activeMenu('kriteria.php', $current) ?>"><span class="icon"><i class="ti ti-ruler-2"></i></span> Data Kriteria</a></li>
    <li><a href="jenis_kulit.php" class="<?= activeMenu('jenis_kulit.php', $current) ?>"><span class="icon"><i class="ti ti-fingerprint"></i></span> Data Jenis Kulit</a></li>
    <li><a href="alternatif.php" class="<?= activeMenu('alternatif.php', $current) ?>"><span class="icon"><i class="ti ti-database"></i></span> Data Alternatif</a></li>
    <li><a href="perhitungan.php" class="<?= activeMenu('perhitungan.php', $current) ?>"><span class="icon"><i class="ti ti-calculator"></i></span> Perhitungan SPK</a></li>
    <li><a href="riwayat_konsultasi.php" class="<?= activeMenu('riwayat_konsultasi.php', $current) ?>"><span class="icon"><i class="ti ti-history"></i></span> Riwayat Konsultasi</a></li>
    <li><a href="logout.php"><span class="icon"><i class="ti ti-logout"></i></span> Logout</a></li>
  </ul>
</aside>