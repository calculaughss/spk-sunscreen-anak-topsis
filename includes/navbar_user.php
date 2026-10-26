<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="navbar">
  <div class="container">
    <a href="<?= BASE_URL ?>index.php" class="logo"><i class="ti ti-sun"></i> SSKids-DSS</a>

    <ul class="nav-menu" id="navMenu">
      <li><a href="<?= BASE_URL ?>index.php">Beranda</a></li>
      <li><a href="<?= BASE_URL ?>index.php#cara-kerja">Cara Kerja</a></li>
      <li><a href="<?= BASE_URL ?>index.php#jenis-kulit">Jenis Kulit</a></li>
    </ul>

    <div class="nav-actions" id="navActions">
      <a href="<?= BASE_URL ?>user/konsultasi.php" class="btn btn-primary btn-sm"><i class="ti ti-bottle"></i> Mulai Konsultasi</a>
    </div>

    <button class="nav-toggle" onclick="toggleNav()"><i class="ti ti-menu-2"></i></button>
  </div>
</nav>
