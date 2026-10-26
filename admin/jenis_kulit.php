<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

$msg = '';
$msgType = 'success';

$opsiPrioritas = ['Physical', 'Chemical', 'Hybrid', 'Semua'];

// ====== TAMBAH / EDIT ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id         = (int) ($_POST['id'] ?? 0);
    $kode       = trim($_POST['kode']);
    $nama       = trim($_POST['nama_jenis_kulit']);
    $prioritas  = isset($_POST['prioritas_sunscreen']) ? implode(',', $_POST['prioritas_sunscreen']) : '';
    $deskripsi  = trim($_POST['deskripsi']);

    if ($prioritas === '') {
        $msg = 'Pilih minimal 1 prioritas sunscreen.';
        $msgType = 'danger';
    } else {
        try {
            if ($id > 0) {
                // UPDATE
                $stmt = $koneksi->prepare("UPDATE jenis_kulit SET kode=?, nama_jenis_kulit=?, prioritas_sunscreen=?, deskripsi=? WHERE id=?");
                $stmt->bind_param('ssssi', $kode, $nama, $prioritas, $deskripsi, $id);
                $stmt->execute();
                $msg = 'Data jenis kulit berhasil diperbarui.';
            } else {
                // INSERT
                $stmt = $koneksi->prepare("INSERT INTO jenis_kulit (kode, nama_jenis_kulit, prioritas_sunscreen, deskripsi) VALUES (?,?,?,?)");
                $stmt->bind_param('ssss', $kode, $nama, $prioritas, $deskripsi);
                $stmt->execute();
                $msg = 'Data jenis kulit berhasil ditambahkan.';
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                $msg = 'Kode jenis kulit sudah digunakan, silakan gunakan kode lain.';
            } else {
                $msg = 'Gagal menyimpan data: ' . $e->getMessage();
            }
            $msgType = 'danger';
        }
    }
}

// ====== HAPUS ======
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $koneksi->prepare("DELETE FROM jenis_kulit WHERE id = ?");
    $stmt->bind_param('i', $id);

    try {
        $stmt->execute();
        $msg = 'Data jenis kulit berhasil dihapus.';
        $msgType = 'success';
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1451) {
            $msg = 'Data tidak bisa dihapus karena masih digunakan pada data konsultasi yang sudah ada.';
        } else {
            $msg = 'Gagal menghapus data: ' . $e->getMessage();
        }
        $msgType = 'danger';
    }
}

$data = [];
$res = $koneksi->query("SELECT * FROM jenis_kulit ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $data[] = $row;

$pageTitle = 'Data Jenis Kulit';
include '../includes/admin_header.php';
?>

<div class="page-title-bar">
  <h2 style="display:flex; align-items:center; gap:8px;"><i class="ti ti-fingerprint"></i> Data Jenis Kulit &amp; Aturan Prioritas</h2>
  <button class="btn btn-primary btn-sm" onclick="openModalTambah()"><i class="ti ti-plus"></i> Tambah Jenis Kulit</button>
</div>

<?php if ($msg): ?>
  <div class="alert alert-<?= $msgType ?>"><?= $msg ?></div>
<?php endif; ?>

<div class="table-wrapper">
  <table>
    <thead>
      <tr><th>Kode</th><th>Jenis Kulit</th><th>Prioritas Sunscreen</th><th>Deskripsi</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="5" style="text-align:center; padding:30px;">Belum ada data jenis kulit.</td></tr>
      <?php endif; ?>
      <?php foreach ($data as $d): ?>
      <tr>
        <td><span class="badge badge-yellow"><?= htmlspecialchars($d['kode']) ?></span></td>
        <td><strong><?= htmlspecialchars($d['nama_jenis_kulit']) ?></strong></td>
        <td>
          <?php foreach (explode(',', $d['prioritas_sunscreen']) as $p): ?>
            <span class="badge badge-blue" style="margin:2px;"><?= htmlspecialchars(trim($p)) ?></span>
          <?php endforeach; ?>
        </td>
        <td style="max-width:340px;"><?= htmlspecialchars($d['deskripsi']) ?></td>
        <td>
          <div class="action-icons">
            <button class="edit" title="Edit" onclick='isiForm(<?= json_encode($d) ?>)'><i class="ti ti-pencil"></i></button>
            <form id="del<?= $d['id'] ?>" method="GET" style="display:inline;">
              <input type="hidden" name="hapus" value="<?= $d['id'] ?>">
              <button type="button" class="delete" title="Hapus" onclick="confirmDelete('del<?= $d['id'] ?>')"><i class="ti ti-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- MODAL TAMBAH/EDIT -->
<div class="modal-overlay" id="modalEdit">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modalEdit')"><i class="ti ti-x"></i></button>
    <h3 id="modalTitle">Tambah Jenis Kulit</h3>
    <form method="POST">
      <input type="hidden" name="id" id="f_id" value="0">

      <div class="form-group">
        <label>Kode</label>
        <input type="text" name="kode" id="f_kode" class="form-control" placeholder="mis. sensitif" required>
      </div>

      <div class="form-group">
        <label>Nama Jenis Kulit</label>
        <input type="text" name="nama_jenis_kulit" id="f_nama" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Prioritas Sunscreen (bisa pilih lebih dari 1)</label>
        <div class="checkbox-group" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:6px;">
          <?php foreach ($opsiPrioritas as $opsi): ?>
            <label style="display:flex; align-items:center; gap:6px; font-weight:400;">
              <input type="checkbox" name="prioritas_sunscreen[]" value="<?= $opsi ?>" class="f_prioritas_checkbox">
              <?= $opsi ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" id="f_deskripsi" class="form-control"></textarea>
      </div>

      <button type="submit" name="simpan" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Simpan</button>
    </form>
  </div>
</div>

<script>
function isiForm(d) {
  document.getElementById('modalTitle').textContent = 'Edit ' + d.nama_jenis_kulit;
  document.getElementById('f_id').value = d.id;
  document.getElementById('f_kode').value = d.kode;
  document.getElementById('f_nama').value = d.nama_jenis_kulit;
  document.getElementById('f_deskripsi').value = d.deskripsi;

  const selected = d.prioritas_sunscreen.split(',').map(s => s.trim());
  document.querySelectorAll('.f_prioritas_checkbox').forEach(cb => {
    cb.checked = selected.includes(cb.value);
  });

  openModal('modalEdit');
}

function openModalTambah() {
  document.getElementById('modalTitle').textContent = 'Tambah Jenis Kulit';
  document.getElementById('f_id').value = 0;
  document.getElementById('f_kode').value = '';
  document.getElementById('f_nama').value = '';
  document.getElementById('f_deskripsi').value = '';
  document.querySelectorAll('.f_prioritas_checkbox').forEach(cb => cb.checked = false);
  openModal('modalEdit');
}
</script>

<?php include '../includes/admin_footer.php'; ?>