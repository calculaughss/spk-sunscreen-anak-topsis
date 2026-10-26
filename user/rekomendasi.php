<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Hitung peringkat SPK (TOPSIS) untuk SELURUH produk, tanpa terikat jenis kulit tertentu
$spk = prosesSPK($koneksi, null);

// Ambil detail produk untuk tiap baris ranking
$hasil = [];
foreach ($spk['ranking'] as $r) {
    $produk = $spk['produk'][$r['sunscreen_id']] ?? null;
    if ($produk) {
        $hasil[] = array_merge($produk, [
            'sunscreen_id' => $r['sunscreen_id'],
            'nilai_akhir'  => $r['nilai_akhir'],
            'ranking'      => $r['ranking'],
        ]);
    }
}

// Daftar seluruh jenis kulit, untuk label "cocok untuk kulit apa saja" di tiap produk
$daftar_jenis_kulit = [];
$res = $koneksi->query("SELECT * FROM jenis_kulit ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $daftar_jenis_kulit[] = $row;

$pageTitle = 'Peringkat Rekomendasi';
include '../includes/header.php';
include '../includes/navbar_user.php';
?>
<div class="page-wrapper">
  <div class="container">

    <div class="section-header" style="margin-bottom:8px;">
        <span class="eyebrow">Peringkat Sunscreen</span>
        <h2>Seluruh Produk, Diurutkan Skor SPK (TOPSIS) <i class="ti ti-list-numbers"></i></h2>
    </div>

    <p style="color:var(--text-muted); font-size:14px; margin:0 auto 28px; max-width:640px; text-align:center;">
        Berikut seluruh produk sunscreen yang tersedia, diurutkan dari skor SPK (TOPSIS) tertinggi
        berdasarkan seluruh kriteria (SPF, PA, kandungan, hypoallergenic, water resistant, harga,
        ukuran). Setiap produk juga diberi label jenis kulit yang cocok. Untuk rekomendasi yang
        dipersonalisasi sesuai anak Anda, gunakan menu <a href="konsultasi.php">Konsultasi</a>.
    </p>

    <?php if (empty($hasil)): ?>
      <div class="empty-state card">
        <div class="emoji"><i class="ti ti-flask-off"></i></div>
        <h3>Belum ada produk sunscreen</h3>
        <p>Silakan tambahkan data sunscreen melalui halaman admin.</p>
      </div>
    <?php else: ?>

    <div class="other-list">
      <?php foreach ($hasil as $h):
          $cocokUntuk = jenisKulitCocokUntukProduk($h, $daftar_jenis_kulit);
      ?>
      <div class="other-item">
        <div class="rank-number">#<?= $h['ranking'] ?></div>
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
        <a href="detail_produk.php?id=<?= $h['sunscreen_id'] ?>" class="btn btn-outline btn-sm">Detail</a>
      </div>
      <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <div style="display:flex; gap:12px; justify-content:center; margin-top:36px; flex-wrap:wrap;">
      <a href="konsultasi.php" class="btn btn-primary"><i class="ti ti-bottle"></i> Dapatkan Rekomendasi Personal</a>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
