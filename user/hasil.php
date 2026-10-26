<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$konsultasi_id = (int) ($_GET['id'] ?? 0);

// Ambil data konsultasi
$stmt = $koneksi->prepare("SELECT k.*, jk.nama_jenis_kulit, jk.kode AS kode_kulit, jk.prioritas_sunscreen
                            FROM konsultasi k
                            JOIN jenis_kulit jk ON jk.id = k.jenis_kulit_id
                            WHERE k.id = ?");
$stmt->bind_param('i', $konsultasi_id);
$stmt->execute();
$konsultasi = $stmt->get_result()->fetch_assoc();

if (!$konsultasi) {
    header('Location: konsultasi.php');
    exit;
}

// Ambil seluruh hasil rekomendasi (ranking asli hasil TOPSIS)
$stmt = $koneksi->prepare("SELECT hr.*, s.* FROM hasil_rekomendasi hr
                            JOIN sunscreen s ON s.id = hr.sunscreen_id
                            WHERE hr.konsultasi_id = ? ORDER BY hr.ranking ASC");
$stmt->bind_param('i', $konsultasi_id);
$stmt->execute();
$hasil = [];
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $hasil[] = $row;

// Ambil produk yang sesuai dengan prioritas jenis kulit anak (tetap urut dari ranking TOPSIS)
$rekomendasiSesuai = array_values(array_filter($hasil, function ($h) use ($konsultasi) {
    return cekCocokJenisKulit($h, $konsultasi);
}));

// Ambil 5 teratas. Jika produk yang cocok jenis kulit kurang dari 5, lengkapi dari sisa hasil ranking.
$top5 = array_slice($rekomendasiSesuai, 0, 5);
if (count($top5) < 5) {
    $idsSudahAda = array_column($top5, 'sunscreen_id');
    foreach ($hasil as $h) {
        if (count($top5) >= 5) break;
        if (!in_array($h['sunscreen_id'], $idsSudahAda, true)) {
            $top5[] = $h;
            $idsSudahAda[] = $h['sunscreen_id'];
        }
    }
}

// Sisa produk lain (ranking #6 - #15) di luar top 5, tetap mengikuti urutan skor SPK asli
$idsTop5 = array_column($top5, 'sunscreen_id');
$sisaProduk = array_values(array_filter($hasil, function ($h) use ($idsTop5) {
    return !in_array($h['sunscreen_id'], $idsTop5, true);
}));
$sisaProduk = array_slice($sisaProduk, 0, 10); // #6 s/d #15

// Daftar seluruh jenis kulit, untuk menampilkan label "cocok untuk kulit apa saja" di tiap produk
$daftar_jenis_kulit = [];
$res2 = $koneksi->query("SELECT * FROM jenis_kulit ORDER BY id ASC");
while ($row = $res2->fetch_assoc()) $daftar_jenis_kulit[] = $row;

$pageTitle = 'Hasil Rekomendasi';
include '../includes/header.php';
include '../includes/navbar_user.php';
?>
<div class="page-wrapper">
  <div class="container">

    <div class="result-summary">
      <div>
        <span class="eyebrow">Hasil Rekomendasi</span>
        <h2>Untuk <?= htmlspecialchars($konsultasi['nama_anak']) ?> (<?= (int) $konsultasi['umur_anak'] ?> tahun)</h2>
      </div>
      <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <span class="badge badge-yellow"><i class="ti <?= iconJenisKulit($konsultasi['kode_kulit']) ?>"></i> Kulit <?= htmlspecialchars($konsultasi['nama_jenis_kulit']) ?></span>
      </div>
    </div>

    <?php if (empty($top5)): ?>
      <div class="empty-state card">
        <div class="emoji"><i class="ti ti-flask-off"></i></div>
        <h3>Belum ada produk yang sesuai</h3>
        <p>Silakan tambahkan data sunscreen melalui halaman admin.</p>
      </div>
    <?php else: ?>

    <p style="color:var(--text-muted); font-size:14px; margin-bottom:20px;">
      Berikut 5 rekomendasi sunscreen terbaik untuk kulit <?= htmlspecialchars($konsultasi['nama_jenis_kulit']) ?>,
      diurutkan dari skor SPK (TOPSIS) tertinggi.
    </p>

    <div class="rank-vertical-list">
      <?php foreach ($top5 as $i => $h): ?>
      <div class="rank-item-v">
        <div class="rank-item-v-head">
          <div class="rank-item-v-badge">
              <div class="rank-number">#<?= $i + 1 ?></div>
          </div>
          <img class="rank-item-v-photo" src="<?= fotoUrl($h['foto']) ?>" alt="<?= htmlspecialchars($h['nama_produk']) ?>"
               onerror="this.src='<?= BASE_URL ?>assets/img/produk/default.png'">
          <div class="rank-item-v-info">
            <h3><?= htmlspecialchars($h['nama_produk']) ?></h3>
            <div class="brand"><?= htmlspecialchars($h['merek']) ?> · <?= htmlspecialchars($h['jenis_sunscreen']) ?></div>
            <div class="spec-row">
              <span class="badge badge-yellow">SPF <?= $h['spf'] ?></span>
              <span class="badge badge-blue"><?= htmlspecialchars($h['pa']) ?></span>
              <?php if ($h['water_resistant'] === 'Ya'): ?><span class="badge badge-green"><i class="ti ti-droplet"></i> Water Resist</span><?php endif; ?>
            </div>
          </div>
          <div class="rank-item-v-score">
            <span class="score-value"><?= number_format($h['nilai_akhir'], 3) ?></span>
            <span class="score-label">Skor SPK</span>
          </div>
        </div>

        <div class="reason-box">
          <span class="label"><i class="ti ti-bulb"></i> Alasan Rekomendasi</span>
          <?= htmlspecialchars($h['alasan']) ?>
        </div>

        <div class="price-size">
          <span><?= rupiah($h['harga']) ?></span>
          <span><?= $h['ukuran'] ?> ml</span>
        </div>

        <a href="detail_produk.php?id=<?= $h['sunscreen_id'] ?>&konsultasi=<?= $konsultasi_id ?>" class="btn btn-outline btn-block">Lihat Detail Produk</a>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if (!empty($sisaProduk)): ?>
    <h3 style="margin:36px 0 4px; display:flex; align-items:center; gap:8px;"><i class="ti ti-list-details"></i> Pilihan Lainnya (Ranking #6 - #<?= 5 + count($sisaProduk) ?>)</h3>
    <p style="color:var(--text-muted); font-size:14px; margin-bottom:16px;">
      Produk lain hasil perhitungan SPK dengan skor lebih rendah, lengkap dengan label jenis kulit yang cocok untuk masing-masing produk.
    </p>
    <div class="other-list">
      <?php foreach ($sisaProduk as $j => $h):
          $cocokUntuk = jenisKulitCocokUntukProduk($h, $daftar_jenis_kulit);
      ?>
      <div class="other-item">
        <div class="rank-number">#<?= 6 + $j ?></div>
        <img src="<?= fotoUrl($h['foto']) ?>" alt="" onerror="this.src='<?= BASE_URL ?>assets/img/produk/default.png'">
        <div class="info">
          <h4><?= htmlspecialchars($h['nama_produk']) ?></h4>
          <span><?= htmlspecialchars($h['merek']) ?> · SPF <?= $h['spf'] ?> · <?= htmlspecialchars($h['pa']) ?> · <?= rupiah($h['harga']) ?></span>
          <div class="cocok-kulit-tags" style="margin-top:6px;">
            <?php if (empty($cocokUntuk)): ?>
              <span style="font-size:11px; color:var(--text-muted);">Belum ada jenis kulit yang cocok secara khusus</span>
            <?php else: ?>
              <span style="font-size:11px; color:var(--text-muted); margin-right:4px;">Cocok untuk kulit:</span>
              <?php foreach ($cocokUntuk as $jk): ?>
                <span class="badge badge-blue" style="font-size:10px; margin:1px;"><i class="ti <?= iconJenisKulit($jk['kode']) ?>"></i> <?= htmlspecialchars($jk['nama_jenis_kulit']) ?></span>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="score">Skor: <?= number_format($h['nilai_akhir'], 3) ?></div>
        <a href="detail_produk.php?id=<?= $h['sunscreen_id'] ?>&konsultasi=<?= $konsultasi_id ?>" class="btn btn-outline btn-sm">Detail</a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>

    <div style="display:flex; gap:12px; justify-content:center; margin-top:36px; flex-wrap:wrap;">
      <a href="konsultasi.php" class="btn btn-primary"><i class="ti ti-refresh"></i> Konsultasi Ulang</a>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
