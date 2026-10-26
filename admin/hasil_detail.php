<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

$id = (int) ($_GET['id'] ?? 0);

$stmt = $koneksi->prepare("SELECT k.*, jk.nama_jenis_kulit
                            FROM konsultasi k
                            JOIN jenis_kulit jk ON jk.id = k.jenis_kulit_id
                            WHERE k.id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$konsultasi = $stmt->get_result()->fetch_assoc();

if (!$konsultasi) { header('Location: riwayat_konsultasi.php'); exit; }

$stmt = $koneksi->prepare("SELECT hr.*, s.nama_produk, s.merek, s.spf, s.pa, s.harga, s.ukuran, s.foto
                            FROM hasil_rekomendasi hr JOIN sunscreen s ON s.id = hr.sunscreen_id
                            WHERE hr.konsultasi_id = ? ORDER BY hr.ranking ASC");
$stmt->bind_param('i', $id);
$stmt->execute();
$hasil = [];
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $hasil[] = $row;

$pageTitle = 'Detail Hasil Konsultasi';
include '../includes/admin_header.php';
?>

<style>
.rank-pill {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  min-width: 46px;
  padding: 4px 10px;
  border-radius: 999px;
  font-weight: 800;
  font-size: 13px;
}
.rank-pill.gold   { background: #FFF6DC; color: #8A6500; border: 1px solid var(--gold); }
.rank-pill.silver { background: #F3F3F3; color: #55575c; border: 1px solid var(--silver); }
.rank-pill.bronze { background: #FBEEE2; color: #8a4a15; border: 1px solid var(--bronze); }
.rank-pill.plain  { background: var(--cream); color: var(--text-muted); }

.detail-info-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px 24px;
}
.detail-info-grid .label { color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
.detail-info-grid .value { font-weight: 700; }
@media (max-width: 600px) { .detail-info-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-title-bar">
  <h2 style="display:flex; align-items:center; gap:8px;"><i class="ti ti-file-text"></i> Detail Hasil Konsultasi #<?= $id ?></h2>
  <a href="riwayat_konsultasi.php" class="btn btn-outline btn-sm"><i class="ti ti-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="margin-bottom:20px;">
  <div class="detail-info-grid">
    <div>
      <div class="label">Nama Anak</div>
      <div class="value"><?= htmlspecialchars($konsultasi['nama_anak']) ?> (<?= $konsultasi['umur_anak'] ?> th, <?= $konsultasi['jenis_kelamin'] ?>)</div>
    </div>
    <div>
      <div class="label">Jenis Kulit</div>
      <div class="value"><span class="badge badge-yellow"><?= htmlspecialchars($konsultasi['nama_jenis_kulit']) ?></span></div>
    </div>
    <div>
      <div class="label">Tanggal Konsultasi</div>
      <div class="value"><?= date('d M Y, H:i', strtotime($konsultasi['tanggal'])) ?></div>
    </div>
  </div>
</div>

<div class="table-wrapper">
  <table>
    <thead>
      <tr><th>Ranking</th><th>Produk</th><th>Merek</th><th>SPF</th><th>PA</th><th>Harga</th><th>Ukuran</th><th>Skor Akhir</th><th>Alasan</th></tr>
    </thead>
    <tbody>
      <?php foreach ($hasil as $h):
        $rankClass = $h['ranking'] === 1 ? 'gold' : ($h['ranking'] === 2 ? 'silver' : ($h['ranking'] === 3 ? 'bronze' : 'plain'));
      ?>
      <tr>
        <td><span class="rank-pill <?= $rankClass ?>"><i class="ti ti-trophy"></i> #<?= $h['ranking'] ?></span></td>
        <td><?= htmlspecialchars($h['nama_produk']) ?></td>
        <td><?= htmlspecialchars($h['merek']) ?></td>
        <td><?= $h['spf'] ?></td>
        <td><?= $h['pa'] ?></td>
        <td><?= rupiah($h['harga']) ?></td>
        <td><?= $h['ukuran'] ?> ml</td>
        <td><strong><?= number_format($h['nilai_akhir'], 5) ?></strong></td>
        <td style="max-width:280px; font-size:13px;"><?= htmlspecialchars($h['alasan']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/admin_footer.php'; ?>