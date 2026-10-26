<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

// Ambil semua produk
$produk = [];
$res = $koneksi->query("SELECT * FROM sunscreen ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $produk[] = $row;

// Bangun matriks menggunakan jenis kulit "normal" (tanpa bonus prioritas) sebagai acuan dasar
$matriks = buatMatriksKeputusan($produk, 'normal');

// Ambil kriteria
$kriteria = [];
$res = $koneksi->query("SELECT * FROM kriteria ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $kriteria[] = $row;

$pageTitle = 'Data Alternatif';
include '../includes/admin_header.php';
?>

<div class="page-title-bar">
  <h2><i class="ti ti-database"></i> Data Alternatif (Matriks Nilai per Kriteria)</h2>
</div>

<div class="alert alert-info">
  <i class="ti ti-alert-triangle"></i> Setiap produk sunscreen merupakan <strong>alternatif</strong> dalam SPK. Nilai pada tabel di bawah
  merupakan hasil konversi otomatis dari atribut produk ke dalam skala numerik (C1–C9) berdasarkan
  jenis kulit <strong>Normal</strong>. Nilai C1 (Jenis Sunscreen) dapat berubah/mendapat bonus skor
  saat proses perhitungan dijalankan untuk jenis kulit tertentu (lihat menu Perhitungan SPK).
</div>

<div class="table-wrapper">
  <table>
    <thead>
      <tr>
        <th>Alternatif</th>
        <?php foreach ($kriteria as $k): ?>
          <th><?= $k['kode'] ?><br><span style="font-size:11px; font-weight:400;"><?= htmlspecialchars($k['nama_kriteria']) ?></span></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($produk)): ?>
        <tr><td colspan="<?= count($kriteria)+1 ?>" style="text-align:center; padding:30px;">Belum ada data produk.</td></tr>
      <?php endif; ?>
      <?php foreach ($produk as $p): ?>
      <tr>
        <td><strong><?= htmlspecialchars($p['nama_produk']) ?></strong><br><span style="font-size:12px; color:var(--text-muted);"><?= htmlspecialchars($p['merek']) ?></span></td>
        <?php foreach ($kriteria as $k): ?>
          <td><?= rtrim(rtrim(number_format($matriks[$p['id']][$k['kode']], 2), '0'), '.') ?></td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/admin_footer.php'; ?>
