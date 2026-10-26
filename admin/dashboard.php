<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

$total_sunscreen   = $koneksi->query("SELECT COUNT(*) c FROM sunscreen")->fetch_assoc()['c'];
$total_kriteria    = $koneksi->query("SELECT COUNT(*) c FROM kriteria")->fetch_assoc()['c'];
$total_alternatif  = $total_sunscreen; // alternatif = produk sunscreen
$total_konsultasi  = $koneksi->query("SELECT COUNT(*) c FROM konsultasi")->fetch_assoc()['c'];
$total_rekomendasi = $koneksi->query("SELECT COUNT(*) c FROM hasil_rekomendasi")->fetch_assoc()['c'];

// Konsultasi terbaru
$res = $koneksi->query("SELECT k.*, jk.nama_jenis_kulit, jk.kode AS kode_kulit
                         FROM konsultasi k
                         JOIN jenis_kulit jk ON jk.id = k.jenis_kulit_id
                         ORDER BY k.tanggal DESC LIMIT 5");
$terbaru = [];
while ($row = $res->fetch_assoc()) $terbaru[] = $row;

$pageTitle = 'Dashboard';
include '../includes/admin_header.php';
?>

<div class="grid grid-5">
  <div class="card stat-card">
    <div class="icon-circle yellow"><i class="ti ti-bottle"></i></div>
    <div><div class="stat-number"><?= $total_sunscreen ?></div><div class="stat-label">Data Sunscreen</div></div>
  </div>
  <div class="card stat-card">
    <div class="icon-circle orange"><i class="ti ti-ruler-2"></i></div>
    <div><div class="stat-number"><?= $total_kriteria ?></div><div class="stat-label">Kriteria (C1–C9)</div></div>
  </div>
  <div class="card stat-card">
    <div class="icon-circle"><i class="ti ti-database"></i></div>
    <div><div class="stat-number"><?= $total_alternatif ?></div><div class="stat-label">Alternatif</div></div>
  </div>
  <div class="card stat-card">
    <div class="icon-circle yellow"><i class="ti ti-users"></i></div>
    <div><div class="stat-number"><?= $total_konsultasi ?></div><div class="stat-label">Total Konsultasi</div></div>
  </div>
  <div class="card stat-card">
    <div class="icon-circle orange"><i class="ti ti-trophy"></i></div>
    <div><div class="stat-number"><?= $total_rekomendasi ?></div><div class="stat-label">Rekomendasi Dihasilkan</div></div>
  </div>
</div>

<div class="card" style="margin-top:24px;">
  <h3 style="margin-bottom:16px;"><i class="ti ti-chart-arrows"></i> Konsultasi Terbaru</h3>
  <?php if (empty($terbaru)): ?>
    <div class="empty-state"><div class="emoji"><i class="ti ti-bell-exclamation"></i></div><p>Belum ada data konsultasi.</p></div>
  <?php else: ?>
  <div class="table-wrapper" style="box-shadow:none;">
    <table>
      <thead>
        <tr><th>Nama Anak</th><th>Umur</th><th>Jenis Kulit</th><th>Tanggal</th></tr>
      </thead>
      <tbody>
        <?php foreach ($terbaru as $t): ?>
        <tr>
          <td><?= htmlspecialchars($t['nama_anak']) ?></td>
          <td><?= (int) $t['umur_anak'] ?> th</td>
          <td><span class="badge badge-yellow"><i class="ti <?= iconJenisKulit($t['kode_kulit']) ?>"></i> <?= htmlspecialchars($t['nama_jenis_kulit']) ?></span></td>
          <td><?= date('d M Y, H:i', strtotime($t['tanggal'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php include '../includes/admin_footer.php'; ?>
