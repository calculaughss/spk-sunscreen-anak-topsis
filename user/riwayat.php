<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginUser();

$user_id = $_SESSION['user_id'];

$stmt = $koneksi->prepare("SELECT k.*, jk.nama_jenis_kulit, jk.kode AS kode_kulit
                            FROM konsultasi k
                            JOIN jenis_kulit jk ON jk.id = k.jenis_kulit_id
                            WHERE k.user_id = ? ORDER BY k.tanggal DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$riwayat = [];
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    // ambil produk #1 untuk masing-masing konsultasi
    $s = $koneksi->prepare("SELECT s.nama_produk, hr.nilai_akhir FROM hasil_rekomendasi hr
                             JOIN sunscreen s ON s.id = hr.sunscreen_id
                             WHERE hr.konsultasi_id = ? AND hr.ranking = 1");
    $s->bind_param('i', $row['id']);
    $s->execute();
    $top = $s->get_result()->fetch_assoc();
    $row['top_produk'] = $top['nama_produk'] ?? '-';
    $row['top_skor'] = $top['nilai_akhir'] ?? 0;
    $riwayat[] = $row;
}

$pageTitle = 'Riwayat Konsultasi';
include '../includes/header.php';
include '../includes/navbar_user.php';
?>
<div class="page-wrapper">
  <div class="container">
    <div class="page-title-bar">
      <h2 style="display:flex; align-items:center; gap:8px;"><i class="ti ti-history"></i> Riwayat Konsultasi</h2>
      <a href="konsultasi.php" class="btn btn-primary btn-sm"><i class="ti ti-plus"></i> Konsultasi Baru</a>
    </div>

    <?php if (empty($riwayat)): ?>
      <div class="empty-state card">
        <div class="emoji"><i class="ti ti-flask-off"></i></div>
        <h3>Belum ada riwayat konsultasi</h3>
        <p>Mulai konsultasi pertama Anda untuk si kecil sekarang.</p>
        <br>
        <a href="konsultasi.php" class="btn btn-primary">Mulai Konsultasi</a>
      </div>
    <?php else: ?>
      <div style="display:flex; flex-direction:column; gap:14px;">
        <?php foreach ($riwayat as $r): ?>
        <div class="card" style="display:flex; align-items:center; gap:18px; flex-wrap:wrap;">
          <div class="icon-circle yellow" style="flex-shrink:0;"><i class="ti ti-calendar"></i></div>
          <div style="flex-grow:1; min-width:200px;">
            <h4><?= htmlspecialchars($r['nama_anak']) ?> (<?= $r['umur_anak'] ?> th, <?= $r['jenis_kelamin'] ?>)</h4>
            <span style="font-size:13px; color:var(--text-muted);"><?= date('d M Y, H:i', strtotime($r['tanggal'])) ?></span>
          </div>
          <span class="badge badge-yellow"><i class="ti <?= iconJenisKulit($r['kode_kulit']) ?>"></i> <?= htmlspecialchars($r['nama_jenis_kulit']) ?></span>
          <div style="min-width:180px;">
            <div style="font-size:13px; color:var(--text-muted);">Rekomendasi #1</div>
            <strong><?= htmlspecialchars($r['top_produk']) ?></strong>
          </div>
          <a href="hasil.php?id=<?= $r['id'] ?>" class="btn btn-outline btn-sm">Lihat Hasil</a>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include '../includes/footer.php'; ?>