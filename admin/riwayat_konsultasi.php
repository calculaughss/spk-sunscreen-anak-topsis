<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

$keyword      = trim($_GET['q'] ?? '');
$filter_kulit = $_GET['jenis_kulit'] ?? '';

$sql = "SELECT k.*, jk.kode AS kode_kulit, jk.nama_jenis_kulit,
               s.nama_produk, s.merek, hr.nilai_akhir
        FROM konsultasi k
        JOIN jenis_kulit jk ON jk.id = k.jenis_kulit_id
        LEFT JOIN hasil_rekomendasi hr ON hr.konsultasi_id = k.id AND hr.ranking = 1
        LEFT JOIN sunscreen s ON s.id = hr.sunscreen_id
        WHERE 1=1";

if ($keyword !== '') {
    $kw = $koneksi->real_escape_string($keyword);
    $sql .= " AND k.nama_anak LIKE '%$kw%'";
}
if ($filter_kulit !== '') {
    $sql .= " AND jk.kode = '" . $koneksi->real_escape_string($filter_kulit) . "'";
}
$sql .= " ORDER BY k.tanggal DESC";

$data = [];
$res = $koneksi->query($sql);
while ($row = $res->fetch_assoc()) $data[] = $row;

$jenis_kulit_list = [];
$res2 = $koneksi->query("SELECT * FROM jenis_kulit ORDER BY id ASC");
while ($row = $res2->fetch_assoc()) $jenis_kulit_list[] = $row;

$adaFilter = ($keyword !== '' || $filter_kulit !== '');

$pageTitle = 'Riwayat Konsultasi';
include '../includes/admin_header.php';
?>

<style>
.filter-row {
  display: flex;
  gap: 12px;
  align-items: end;
  flex-wrap: wrap;
}
.filter-row .btn { height: 44px; }
.top-produk { display: flex; align-items: center; gap: 8px; }
.top-produk .icon-circle-sm {
  width: 26px; height: 26px;
  border-radius: 50%;
  background: #FFF6DC;
  color: #8A6500;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px;
  flex-shrink: 0;
}
.score-pill {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-weight: 800;
  font-size: 13px;
  background: var(--cream);
  color: var(--deep-blue);
}
.no-result-text { color: var(--text-muted); font-size: 12px; }
</style>

<div class="page-title-bar">
  <h2><i class="ti ti-history"></i> Riwayat Konsultasi &amp; Hasil Ranking</h2>
</div>

<div class="card" style="margin-bottom:20px;">
  <form method="GET" class="filter-row">
    <div class="form-group" style="margin-bottom:0; flex:1; min-width:220px;">
      <label>Cari Nama Anak / Orang Tua</label>
      <input type="text" name="q" class="form-control" placeholder="Cari nama anak..." value="<?= htmlspecialchars($keyword) ?>">
    </div>
    <div class="form-group" style="margin-bottom:0; min-width:220px;">
      <label>Filter Jenis Kulit</label>
      <select name="jenis_kulit" class="form-control">
        <option value="">Semua Jenis Kulit</option>
        <?php foreach ($jenis_kulit_list as $jk): ?>
          <option value="<?= $jk['kode'] ?>" <?= $filter_kulit === $jk['kode'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($jk['nama_jenis_kulit']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-search"></i> Cari</button>
    <a href="konsultasi.php" class="btn btn-outline btn-sm" style="<?= $adaFilter ? '' : 'visibility:hidden;' ?>">
      <i class="ti ti-refresh"></i> Reset
    </a>
  </form>
</div>

<div class="table-wrapper">
  <table>
    <thead>
      <tr>
        <th>Nama Anak</th><th>Umur</th><th>Jenis Kelamin</th>
        <th>Jenis Kulit</th><th>Produk #1 (Top)</th><th>Skor</th><th>Tanggal</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr>
          <td colspan="8">
            <div class="empty-state">
              <div class="emoji"><i class="ti ti-inbox"></i></div>
              <p>Tidak ada data konsultasi<?= $adaFilter ? ' yang cocok dengan filter ini.' : '.' ?></p>
            </div>
          </td>
        </tr>
      <?php endif; ?>
      <?php foreach ($data as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['nama_anak']) ?></td>
        <td><?= $d['umur_anak'] ?> th</td>
        <td><?= $d['jenis_kelamin'] ?></td>
        <td><span class="badge badge-yellow"><?= htmlspecialchars($d['nama_jenis_kulit']) ?></span></td>
        <td>
          <?php if ($d['nama_produk']): ?>
            <div class="top-produk">
              <span class="icon-circle-sm"><i class="ti ti-trophy"></i></span>
              <div>
                <?= htmlspecialchars($d['nama_produk']) ?>
                <div style="color:var(--text-muted); font-size:12px;"><?= htmlspecialchars($d['merek']) ?></div>
              </div>
            </div>
          <?php else: ?>
            <span class="no-result-text">Belum ada hasil</span>
          <?php endif; ?>
        </td>
        <td><?= $d['nilai_akhir'] !== null ? '<span class="score-pill">' . number_format($d['nilai_akhir'], 3) . '</span>' : '<span class="no-result-text">-</span>' ?></td>
        <td><?= date('d M Y, H:i', strtotime($d['tanggal'])) ?></td>
        <td>
          <a href="hasil_detail.php?id=<?= $d['id'] ?>" class="btn btn-outline btn-sm"><i class="ti ti-eye"></i> Lihat Hasil</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/admin_footer.php'; ?>