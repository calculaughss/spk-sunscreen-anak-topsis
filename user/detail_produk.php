<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$konsultasi_id = (int) ($_GET['konsultasi'] ?? 0);

$stmt = $koneksi->prepare("SELECT * FROM sunscreen WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$produk = $stmt->get_result()->fetch_assoc();

if (!$produk) {
    header('Location: konsultasi.php');
    exit;
}

// Jika diakses dari halaman hasil, tampilkan skor & ranking terkait
$skor = null;
if ($konsultasi_id) {
    $stmt = $koneksi->prepare("SELECT * FROM hasil_rekomendasi WHERE konsultasi_id = ? AND sunscreen_id = ?");
    $stmt->bind_param('ii', $konsultasi_id, $id);
    $stmt->execute();
    $skor = $stmt->get_result()->fetch_assoc();
}

// Label kandungan fragrance & alkohol (kolom gabungan kandungan_fa)
$labelKandungan = [
    'bebas_semua'      => 'Bebas fragrance dan alkohol',
    'bebas_sebagian'   => 'Bebas salah satu kandungan',
    'mengandung_semua' => 'Mengandung fragrance dan alkohol',
];

$pageTitle = 'Detail Produk';
include '../includes/header.php';
include '../includes/navbar_user.php';
?>
<div class="page-wrapper">
  <div class="container">
    <div class="card">
      <div class="grid grid-2" style="align-items:start;">
        <div>
          <img src="<?= fotoUrl($produk['foto']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>"
               style="width:100%; border-radius:var(--radius-md); background:var(--sky-blue); height:320px; object-fit:cover;"
               onerror="this.src='<?= BASE_URL ?>assets/img/produk/default.png'">
        </div>
        <div>
          <span class="eyebrow"><?= htmlspecialchars($produk['merek']) ?></span>
          <h2 style="margin-bottom:12px;"><?= htmlspecialchars($produk['nama_produk']) ?></h2>

          <?php if ($skor): ?>
          <div class="badge badge-yellow" style="margin-bottom:16px; display:inline-flex; align-items:center; gap:6px;">
            <?= medali($skor['ranking']) ?> Peringkat #<?= $skor['ranking'] ?> · Skor <?= number_format($skor['nilai_akhir'], 3) ?>
          </div>
          <?php endif; ?>

          <table style="width:100%; margin-bottom:16px;">
            <tbody>
              <tr><td style="padding:8px 0; font-weight:700;"><i class="ti ti-bottle"></i> Jenis Sunscreen</td><td><?= htmlspecialchars($produk['jenis_sunscreen']) ?></td></tr>
              <tr><td style="padding:8px 0; font-weight:700;"><i class="ti ti-sun"></i> SPF</td><td><?= $produk['spf'] ?></td></tr>
              <tr><td style="padding:8px 0; font-weight:700;"><i class="ti ti-shield"></i> PA</td><td><?= htmlspecialchars($produk['pa']) ?></td></tr>
              <tr><td style="padding:8px 0; font-weight:700;"><i class="ti ti-flask"></i> Kandungan</td><td><?= $labelKandungan[$produk['kandungan_fa']] ?? htmlspecialchars($produk['kandungan_fa']) ?></td></tr>
              <tr><td style="padding:8px 0; font-weight:700;"><i class="ti ti-shield-check"></i> Hypoallergenic</td><td><?= $produk['hypoallergenic'] ?></td></tr>
              <tr><td style="padding:8px 0; font-weight:700;"><i class="ti ti-droplet"></i> Water Resistant</td><td><?= $produk['water_resistant'] ?></td></tr>
              <tr><td style="padding:8px 0; font-weight:700;"><i class="ti ti-coin"></i> Harga</td><td><?= rupiah($produk['harga']) ?></td></tr>
              <tr><td style="padding:8px 0; font-weight:700;"><i class="ti ti-ruler"></i> Ukuran</td><td><?= $produk['ukuran'] ?> ml</td></tr>
            </tbody>
          </table>

          <?php if ($skor): ?>
          <div class="reason-box" style="margin-bottom:16px;">
            <span class="label"><i class="ti ti-bulb"></i> Alasan Rekomendasi</span>
            <?= htmlspecialchars($skor['alasan']) ?>
          </div>
          <?php endif; ?>

          <a href="javascript:history.back()" class="btn btn-outline">
              <i class="ti ti-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>