<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$error = '';

// Ambil data jenis kulit untuk pilihan
$jenis_kulit_list = [];
$res = $koneksi->query("SELECT * FROM jenis_kulit ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $jenis_kulit_list[] = $row;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_anak      = trim($_POST['nama_anak'] ?? '');
    $umur_raw       = trim($_POST['umur_anak'] ?? '');
    $umur_anak      = ($umur_raw === '') ? -1 : (int) $umur_raw; // -1 = penanda "belum diisi"
    $jenis_kelamin  = $_POST['jenis_kelamin'] ?? '';
    $jenis_kulit_id = (int) ($_POST['jenis_kulit_id'] ?? 0);

    if ($nama_anak === '' || $umur_anak < 0 || $jenis_kelamin === '' || $jenis_kulit_id <= 0) {
        $error = 'Mohon lengkapi seluruh data pada form konsultasi.';
    } elseif ($umur_anak > 10) {
        $error = 'Sistem ini khusus untuk anak usia 0-10 tahun. Mohon masukkan umur yang sesuai.';
    } else {
        // 1. Simpan data konsultasi (tanpa akun/login, jadi tanpa user_id)
        $stmt = $koneksi->prepare("INSERT INTO konsultasi (nama_anak, umur_anak, jenis_kelamin, jenis_kulit_id) VALUES (?,?,?,?)");
        $stmt->bind_param('sisi', $nama_anak, $umur_anak, $jenis_kelamin, $jenis_kulit_id);
        $stmt->execute();
        $konsultasi_id = $koneksi->insert_id;

        // 2. Ambil kode jenis kulit
        $stmt = $koneksi->prepare("SELECT kode FROM jenis_kulit WHERE id = ?");
        $stmt->bind_param('i', $jenis_kulit_id);
        $stmt->execute();
        $kode_kulit = $stmt->get_result()->fetch_assoc()['kode'];

        // 3. Proses SPK (filtering rule-based + TOPSIS)
        $spk = prosesSPK($koneksi, $kode_kulit);

        // 4. Simpan hasil rekomendasi
        $ins = $koneksi->prepare("INSERT INTO hasil_rekomendasi (konsultasi_id, sunscreen_id, nilai_akhir, ranking, alasan) VALUES (?,?,?,?,?)");
        foreach ($spk['ranking'] as $r) {
            $produk = $spk['produk'][$r['sunscreen_id']];
            $alasan = generateAlasan($produk, $kode_kulit);
            $ins->bind_param('iidis', $konsultasi_id, $r['sunscreen_id'], $r['nilai_akhir'], $r['ranking'], $alasan);
            $ins->execute();
        }

        header('Location: hasil.php?id=' . $konsultasi_id);
        exit;
    }
}

$pageTitle = 'Konsultasi';
include '../includes/header.php';
include '../includes/navbar_user.php';
?>
<div class="page-wrapper">
  <div class="container">
    <div class="section-header" style="margin-bottom:24px;">
      <span class="eyebrow">Form Konsultasi</span>
      <h2>Ceritakan Tentang Si Kecil <i class="ti ti-mood-kid"></i></h2>
      <p>Isi data berikut agar sistem dapat memberikan rekomendasi sunscreen yang paling sesuai. Tidak perlu login/daftar akun.</p>
    </div>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card konsultasi-card">
      <form method="POST">
        <div class="form-group">
          <label>Nama Anak</label>
          <div class="input-icon"><span><i class="ti ti-mood-kid"></i></span>
            <input type="text" name="nama_anak" class="form-control" required placeholder="Contoh: Aisyah" value="<?= htmlspecialchars($_POST['nama_anak'] ?? '') ?>">
          </div>
        </div>

        <div class="form-group">
          <label>Umur Anak (tahun, maksimal 10 tahun)</label>
          <div class="input-icon"><span><i class="ti ti-cake"></i></span>
            <input type="number" name="umur_anak" class="form-control" min="0" max="10" required placeholder="Contoh: 5" value="<?= htmlspecialchars($_POST['umur_anak'] ?? '') ?>">
          </div>
          <small style="color:var(--text-muted); font-size:12px;">Anak di bawah usia 1 tahun diinput dengan nilai 0.</small>
        </div>

        <div class="form-group">
          <label>Jenis Kelamin</label>
          <div class="gender-options">
            <label class="option-card">
              <input type="radio" name="jenis_kelamin" value="Laki-laki" required <?= (($_POST['jenis_kelamin'] ?? '') === 'Laki-laki') ? 'checked' : '' ?>>
              <span class="emoji"><i class="ti ti-gender-male"></i></span>
              <h5>Laki-laki</h5>
            </label>
            <label class="option-card">
              <input type="radio" name="jenis_kelamin" value="Perempuan" required <?= (($_POST['jenis_kelamin'] ?? '') === 'Perempuan') ? 'checked' : '' ?>>
              <span class="emoji"><i class="ti ti-gender-female"></i></span>
              <h5>Perempuan</h5>
            </label>
          </div>
        </div>

        <div class="form-group">
          <label>Jenis Kulit Anak</label>
          <div class="skin-options">
            <?php foreach ($jenis_kulit_list as $jk):
              $deskripsi = trim($jk['deskripsi']);
              $ringkas = mb_strlen($deskripsi) > 90 ? mb_substr($deskripsi, 0, 90) . '...' : $deskripsi;
              $checked = (int) ($_POST['jenis_kulit_id'] ?? 0) === (int) $jk['id'];
            ?>
            <label class="option-card">
              <input type="radio" name="jenis_kulit_id" value="<?= $jk['id'] ?>" required <?= $checked ? 'checked' : '' ?>>
              <span class="emoji"><i class="ti <?= iconJenisKulit($jk['kode']) ?>"></i></span>
              <h5><?= htmlspecialchars($jk['nama_jenis_kulit']) ?></h5>
              <p><?= htmlspecialchars($ringkas) ?></p>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="alert alert-info">
          <i class="ti ti-info-circle"></i> Sistem akan menyaring produk sesuai jenis kulit yang dipilih, lalu menghitung skor
          akhir menggunakan metode SPK (TOPSIS) dan menampilkan 5 rekomendasi terbaik.
        </div>

        <button type="submit" class="btn btn-primary btn-block">Proses &amp; Lihat Rekomendasi <i class="ti ti-arrow-right"></i></button>
      </form>
    </div>
  </div>
</div>

<style>
.skin-options {
  display: flex !important;
  flex-wrap: wrap;
  justify-content: center;
  align-items: stretch;
  gap: 14px;
}
.skin-options .option-card {
  flex: 0 1 180px;
  display: flex;
  flex-direction: column;
}
.skin-options .option-card p {
  flex-grow: 1;
}
@media (max-width: 480px) {
  .skin-options .option-card { flex: 1 1 45%; }
}
</style>
<?php include '../includes/footer.php'; ?>
