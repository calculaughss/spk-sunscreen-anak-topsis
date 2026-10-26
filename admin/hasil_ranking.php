<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

// Filter
$filter_kulit = $_GET['jenis_kulit'] ?? '';

$sql = "SELECT k.id AS konsultasi_id, k.nama_anak, k.tanggal, jk.nama_jenis_kulit, jk.kode AS kode_kulit,
               s.nama_produk, s.merek, hr.nilai_akhir
        FROM konsultasi k
        JOIN jenis_kulit jk ON jk.id = k.jenis_kulit_id
        JOIN hasil_rekomendasi hr ON hr.konsultasi_id = k.id AND hr.ranking = 1
        JOIN sunscreen s ON s.id = hr.sunscreen_id";
if ($filter_kulit !== '') {
    $sql .= " WHERE jk.kode = '" . $koneksi->real_escape_string($filter_kulit) . "'";
}
$sql .= " ORDER BY k.tanggal DESC";

$data = [];
$res = $koneksi->query($sql);
while ($row = $res->fetch_assoc()) $data[] = $row;

$jenis_kulit_list = [];
$res2 = $koneksi->query("SELECT * FROM jenis_kulit ORDER BY id ASC");
while ($row = $res2->fetch_assoc()) $jenis_kulit_list[] = $row;

$pageTitle = 'Hasil Ranking';
include '../includes/admin_header.php';
?>

<div class="page-title-bar">
  <h2>🏆 Hasil Ranking (Rekomendasi #1 per Konsultasi)</h2>
</div>

<div class="card" style="margin-bottom:20px;">
  <form method="GET" style="display:flex; gap:12px; align-items:end; flex-wrap:wrap;">
    <div class="form-group" style="margin-bottom:0; flex:1; min-width:220px;">
      <label>Filter Jenis Kulit</label>
      <select name="jenis_kulit" class="form-control" onchange="this.form.submit()">
        <option value="">Semua Jenis Kulit</option>
        <?php foreach ($jenis_kulit_list as $jk): ?>
          <option value="<?= $jk['kode'] ?>" <?= $filter_kulit === $jk['kode'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($jk['nama_jenis_kulit']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </form>
</div>

<div class="table-wrapper">
  <table>
    <thead>
      <tr><th>ID Konsultasi</th><th>Nama Anak</th><th>Jenis Kulit</th><th>Produk #1 (Top)</th><th>Skor</th><th>Tanggal</th></tr>
    </thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="6" style="text-align:center; padding:30px;">Belum ada hasil ranking.</td></tr>
      <?php endif; ?>
      <?php foreach ($data as $d): ?>
      <tr>
        <td>#<?= $d['konsultasi_id'] ?></td>
        <td><?= htmlspecialchars($d['nama_anak']) ?></td>
        <td><span class="badge badge-yellow"><i class="ti <?= iconJenisKulit($d['kode_kulit']) ?>"></i> <?= htmlspecialchars($d['nama_jenis_kulit']) ?></span></td>
        <td>🥇 <?= htmlspecialchars($d['nama_produk']) ?> <span style="color:var(--text-muted); font-size:12px;">(<?= htmlspecialchars($d['merek']) ?>)</span></td>
        <td><strong><?= number_format($d['nilai_akhir'], 3) ?></strong></td>
        <td><?= date('d M Y, H:i', strtotime($d['tanggal'])) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/admin_footer.php'; ?>
