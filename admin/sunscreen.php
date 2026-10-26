<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

$msg = '';
$msgType = 'success';

// ====== TAMBAH / EDIT ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id              = (int) ($_POST['id'] ?? 0);
    $nama_produk     = trim($_POST['nama_produk']);
    $merek           = trim($_POST['merek']);
    $jenis_sunscreen = $_POST['jenis_sunscreen'];
    $spf             = (int) $_POST['spf'];
    $pa              = $_POST['pa'];
    $kandungan_fa    = $_POST['kandungan_fa']; // gabungan fragrance & alkohol
    $hypoallergenic  = $_POST['hypoallergenic'];
    $water_resistant = $_POST['water_resistant'];
    $harga           = (float) $_POST['harga'];
    $ukuran          = (int) $_POST['ukuran'];
    $foto            = 'default.png';

    // Upload foto jika ada
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $foto = 'sunscreen_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], '../assets/img/produk/' . $foto);
        }
    }

    try {
        if ($id > 0) {
            // UPDATE
            if ($foto === 'default.png' && !empty($_POST['foto_lama'])) {
                $foto = $_POST['foto_lama'];
            }
            $stmt = $koneksi->prepare("UPDATE sunscreen SET nama_produk=?, merek=?, jenis_sunscreen=?, spf=?, pa=?, kandungan_fa=?, hypoallergenic=?, water_resistant=?, harga=?, ukuran=?, foto=? WHERE id=?");
            $stmt->bind_param('sssissssdisi', $nama_produk, $merek, $jenis_sunscreen, $spf, $pa, $kandungan_fa, $hypoallergenic, $water_resistant, $harga, $ukuran, $foto, $id);
            $stmt->execute();
            $msg = 'Data sunscreen berhasil diperbarui.';
        } else {
            // INSERT
            $stmt = $koneksi->prepare("INSERT INTO sunscreen (nama_produk, merek, jenis_sunscreen, spf, pa, kandungan_fa, hypoallergenic, water_resistant, harga, ukuran, foto) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param('sssissssdis', $nama_produk, $merek, $jenis_sunscreen, $spf, $pa, $kandungan_fa, $hypoallergenic, $water_resistant, $harga, $ukuran, $foto);
            $stmt->execute();
            $msg = 'Data sunscreen berhasil ditambahkan.';
        }
    } catch (mysqli_sql_exception $e) {
        $msg = 'Gagal menyimpan data: ' . $e->getMessage();
        $msgType = 'danger';
    }
}

// ====== HAPUS ======
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $koneksi->prepare("DELETE FROM sunscreen WHERE id = ?");
    $stmt->bind_param('i', $id);

    try {
        $stmt->execute();
        $msg = 'Data sunscreen berhasil dihapus.';
        $msgType = 'success';
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1451) {
            $msg = 'Data tidak bisa dihapus karena masih digunakan di data rekomendasi lain.';
        } else {
            $msg = 'Gagal menghapus data: ' . $e->getMessage();
        }
        $msgType = 'danger';
    }
}

// ====== AMBIL DATA ======
$data = [];
$res = $koneksi->query("SELECT * FROM sunscreen ORDER BY id DESC");
while ($row = $res->fetch_assoc()) $data[] = $row;

// Label untuk ditampilkan di tabel/modal
$labelKandungan = [
    'bebas_semua'      => 'Bebas fragrance dan alkohol',
    'bebas_sebagian'   => 'Bebas salah satu kandungan',
    'mengandung_semua' => 'Mengandung fragrance dan alkohol',
];

$pageTitle = 'Data Sunscreen';
include '../includes/admin_header.php';
?>

<div class="page-title-bar">
  <h2 style="display:flex; align-items:center; gap:8px;"><i class="ti ti-bottle"></i> Data Sunscreen</h2>
  <button class="btn btn-primary btn-sm" onclick="openModal('modalTambah')"><i class="ti ti-plus"></i> Tambah Produk</button>
</div>

<?php if ($msg): ?><div class="alert alert-<?= $msgType ?>"><?= $msg ?></div><?php endif; ?>

<div class="table-wrapper">
  <table>
    <thead>
      <tr>
        <th>Foto</th><th>Nama Produk</th><th>Merek</th><th>Jenis</th><th>SPF</th><th>PA</th>
        <th>Kandungan</th><th>Harga</th><th>Ukuran</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="10" style="text-align:center; padding:30px;">Belum ada data sunscreen.</td></tr>
      <?php endif; ?>
      <?php foreach ($data as $d): ?>
      <tr>
        <td><img class="table-photo" src="<?= fotoUrl($d['foto']) ?>" onerror="this.src='<?= BASE_URL ?>assets/img/produk/default.png'"></td>
        <td><?= htmlspecialchars($d['nama_produk']) ?></td>
        <td><?= htmlspecialchars($d['merek']) ?></td>
        <td><?= htmlspecialchars($d['jenis_sunscreen']) ?></td>
        <td><?= $d['spf'] ?></td>
        <td><?= $d['pa'] ?></td>
        <td><span class="badge badge-blue" style="font-size:11px;"><?= $labelKandungan[$d['kandungan_fa']] ?? $d['kandungan_fa'] ?></span></td>
        <td><?= rupiah($d['harga']) ?></td>
        <td><?= $d['ukuran'] ?> ml</td>
        <td>
          <div class="action-icons">
              <button class="edit"
                      title="Edit"
                      onclick='isiFormEdit(<?= htmlspecialchars(json_encode($d), ENT_QUOTES, "UTF-8") ?>)'>
                  <i class="ti ti-pencil"></i>
              </button>

              <form id="del<?= $d['id'] ?>" method="GET" style="display:inline;">
                  <input type="hidden" name="hapus" value="<?= $d['id'] ?>">
                  <button type="button"
                          class="delete"
                          title="Hapus"
                          onclick="confirmDelete('del<?= $d['id'] ?>')">
                      <i class="ti ti-trash"></i>
                  </button>
              </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- MODAL TAMBAH/EDIT -->
<div class="modal-overlay" id="modalTambah">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modalTambah')"><i class="ti ti-x"></i></button>
    <h3 id="modalTitle">Tambah Produk Sunscreen</h3>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" id="f_id">
      <input type="hidden" name="foto_lama" id="f_foto_lama">

      <div class="grid grid-2">
        <div class="form-group">
          <label>Nama Produk</label>
          <input type="text" name="nama_produk" id="f_nama_produk" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Merek</label>
          <input type="text" name="merek" id="f_merek" class="form-control" required>
        </div>
      </div>

      <div class="grid grid-2">
        <div class="form-group">
          <label>Jenis Sunscreen</label>
          <select name="jenis_sunscreen" id="f_jenis_sunscreen" class="form-control" required>
            <option value="Physical">Physical</option>
            <option value="Chemical">Chemical</option>
            <option value="Hybrid">Hybrid</option>
          </select>
        </div>
        <div class="form-group">
          <label>SPF</label>
          <input type="number" name="spf" id="f_spf" class="form-control" min="1" max="100" required>
        </div>
      </div>

      <div class="grid grid-2">
        <div class="form-group">
          <label>PA</label>
          <select name="pa" id="f_pa" class="form-control" required>
            <option value="PA+">PA+</option>
            <option value="PA++">PA++</option>
            <option value="PA+++">PA+++</option>
            <option value="PA++++">PA++++</option>
          </select>
        </div>
        <div class="form-group">
          <label>Harga (Rp)</label>
          <input type="number" name="harga" id="f_harga" class="form-control" required>
        </div>
      </div>

      <div class="grid grid-2">
        <div class="form-group">
          <label>Ukuran (ml)</label>
          <input type="number" name="ukuran" id="f_ukuran" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Foto Produk</label>
          <input type="file" name="foto" id="f_foto" class="form-control" accept="image/*" onchange="previewFoto(this,'previewFoto')">
          <img id="previewFoto" style="display:none; margin-top:8px; width:80px; height:80px; object-fit:cover; border-radius:8px;">
        </div>
      </div>

      <div class="form-group" style="margin-bottom:10px;">
        <label>Kandungan Fragrance &amp; Alkohol</label>
        <select name="kandungan_fa" id="f_kandungan_fa" class="form-control" required>
          <option value="bebas_semua">Bebas fragrance dan alkohol</option>
          <option value="bebas_sebagian">Bebas salah satu kandungan</option>
          <option value="mengandung_semua">Mengandung fragrance dan alkohol</option>
        </select>
      </div>

      <div class="grid grid-2" style="margin-bottom:10px;">
        <div class="form-group">
          <label>Hypoallergenic</label>
          <select name="hypoallergenic" id="f_hypoallergenic" class="form-control"><option value="Ya">Ya</option><option value="Tidak">Tidak</option></select>
        </div>
        <div class="form-group">
          <label>Water Resistant</label>
          <select name="water_resistant" id="f_water_resistant" class="form-control"><option value="Tidak">Tidak</option><option value="Ya">Ya</option></select>
        </div>
      </div>

      <button type="submit" name="simpan" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Simpan Data</button>
    </form>
  </div>
</div>

<script>
function isiFormEdit(d) {
  document.getElementById('modalTitle').textContent = 'Edit Produk Sunscreen';
  document.getElementById('f_id').value = d.id;
  document.getElementById('f_foto_lama').value = d.foto;
  document.getElementById('f_nama_produk').value = d.nama_produk;
  document.getElementById('f_merek').value = d.merek;
  document.getElementById('f_jenis_sunscreen').value = d.jenis_sunscreen;
  document.getElementById('f_spf').value = d.spf;
  document.getElementById('f_pa').value = d.pa;
  document.getElementById('f_harga').value = d.harga;
  document.getElementById('f_ukuran').value = d.ukuran;
  document.getElementById('f_kandungan_fa').value = d.kandungan_fa;
  document.getElementById('f_hypoallergenic').value = d.hypoallergenic;
  document.getElementById('f_water_resistant').value = d.water_resistant;
  openModal('modalTambah');
}
</script>

<?php include '../includes/admin_footer.php'; ?>