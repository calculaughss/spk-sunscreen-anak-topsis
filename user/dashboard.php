<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginUser();

$user_id = $_SESSION['user_id'];

// Total konsultasi
$stmt = $koneksi->prepare("SELECT COUNT(*) AS total FROM konsultasi WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$total_konsultasi = $stmt->get_result()->fetch_assoc()['total'];

// Konsultasi terakhir
$stmt = $koneksi->prepare("SELECT k.*, jk.nama_jenis_kulit
                            FROM konsultasi k
                            JOIN jenis_kulit jk ON jk.id = k.jenis_kulit_id
                            WHERE k.user_id = ? ORDER BY k.tanggal DESC LIMIT 1");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$last_konsultasi = $stmt->get_result()->fetch_assoc();

$top_produk = null;
if ($last_konsultasi) {
    $stmt = $koneksi->prepare("SELECT hr.*, s.nama_produk, s.merek, s.foto, s.spf, s.pa
                                FROM hasil_rekomendasi hr
                                JOIN sunscreen s ON s.id = hr.sunscreen_id
                                WHERE hr.konsultasi_id = ? AND hr.ranking = 1");
    $stmt->bind_param('i', $last_konsultasi['id']);
    $stmt->execute();
    $top_produk = $stmt->get_result()->fetch_assoc();
}

$pageTitle = 'Dashboard';
include '../includes/header.php';
include '../includes/navbar_user.php';
?>

<style>
.app-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: .5px;
  background: rgba(255,255,255,0.18);
  color: var(--white);
  padding: 4px 12px;
  border-radius: 999px;
  margin-bottom: 10px;
}
.quick-card { text-decoration: none; }
.quick-card .icon-circle { font-size: 26px; }
.top-produk-preview {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 8px 0 12px;
}
.top-produk-preview img {
  width: 52px; height: 52px;
  border-radius: var(--radius-sm);
  object-fit: cover;
  background: var(--cream);
  flex-shrink: 0;
}
.top-produk-preview .spec-row { margin-bottom: 0; }
</style>

<div class="page-wrapper">
  <div class="container">
    <div class="greeting-card">
      <span class="app-badge"><i class="ti ti-sun"></i> SunScreen Kids Decision Support System (SSK-DSS)</span>
      <h2>Halo, <?= htmlspecialchars($_SESSION['user_nama']) ?>!</h2>
      <p>Selamat datang kembali. Yuk, lindungi kulit si kecil dengan sunscreen terbaik!</p>
    </div>

    <div class="grid grid-3">
      <a href="konsultasi.php" class="card quick-card">
        <div class="icon-circle yellow"><i class="ti ti-bottle"></i></div>
        <h4>Mulai Konsultasi Baru</h4>
        <p>Isi data anak untuk mendapatkan rekomendasi sunscreen terbaru.</p>
      </a>
      <a href="riwayat.php" class="card quick-card">
        <div class="icon-circle orange"><i class="ti ti-history"></i></div>
        <h4>Riwayat Konsultasi</h4>
        <p>Anda telah melakukan <strong><?= $total_konsultasi ?></strong> kali konsultasi.</p>
      </a>
      <div class="card quick-card">
        <div class="icon-circle"><i class="ti ti-trophy"></i></div>
        <h4>Rekomendasi Terakhir</h4>
        <?php if ($top_produk): ?>
          <div class="top-produk-preview">
            <img src="<?= fotoUrl($top_produk['foto']) ?>" onerror="this.src='<?= BASE_URL ?>assets/img/produk/default.png'" alt="<?= htmlspecialchars($top_produk['nama_produk']) ?>">
            <div>
              <div style="font-weight:700; font-size:14px;"><?= htmlspecialchars($top_produk['nama_produk']) ?></div>
              <div style="color:var(--text-muted); font-size:12px;"><?= htmlspecialchars($top_produk['merek']) ?> &middot; SPF <?= htmlspecialchars($top_produk['spf']) ?> &middot; <?= htmlspecialchars($top_produk['pa']) ?></div>
            </div>
          </div>
          <a href="hasil.php?id=<?= $last_konsultasi['id'] ?>" class="btn btn-outline btn-sm"><i class="ti ti-eye"></i> Lihat Detail</a>
        <?php else: ?>
          <p>Belum ada riwayat konsultasi.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>