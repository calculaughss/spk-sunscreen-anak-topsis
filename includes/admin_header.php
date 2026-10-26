<?php if (!isset($pageTitle)) $pageTitle = 'Admin'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> | Admin SunKids</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;700;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="admin-wrapper">
  <?php include '../includes/navbar_admin.php'; ?>
  <div class="admin-main">
    <div class="topbar">
      <div style="display:flex; align-items:center; gap:14px;">
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
        <h2><?= htmlspecialchars($pageTitle) ?></h2>
      </div>
      <div class="admin-user">
        <div class="avatar"><?= strtoupper(substr($_SESSION['admin_nama'] ?? 'A', 0, 1)) ?></div>
        <span><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Administrator') ?></span>
      </div>
    </div>
    <div class="admin-content">
