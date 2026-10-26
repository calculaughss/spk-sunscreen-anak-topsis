<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

$msg = '';
$msgType = 'success';

// ====== TAMBAH / EDIT KRITERIA (tanpa bobot) ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id            = (int) ($_POST['id'] ?? 0);
    $kode          = trim($_POST['kode']);
    $nama_kriteria = trim($_POST['nama_kriteria']);
    $tipe          = $_POST['tipe'];
    $keterangan    = trim($_POST['keterangan']);

    try {
        if ($id > 0) {
            // UPDATE (bobot tidak diikutkan)
            $stmt = $koneksi->prepare("UPDATE kriteria SET kode=?, nama_kriteria=?, tipe=?, keterangan=? WHERE id=?");
            $stmt->bind_param('ssssi', $kode, $nama_kriteria, $tipe, $keterangan, $id);
            $stmt->execute();
            $msg = 'Data kriteria berhasil diperbarui.';
        } else {
            // INSERT (bobot default 0, diatur di tabel bobot pada halaman ini)
            $stmt = $koneksi->prepare("INSERT INTO kriteria (kode, nama_kriteria, tipe, bobot, keterangan) VALUES (?,?,?,0,?)");
            $stmt->bind_param('ssss', $kode, $nama_kriteria, $tipe, $keterangan);
            $stmt->execute();
            $msg = 'Data kriteria berhasil ditambahkan. Silakan atur bobotnya di tabel bawah.';
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) {
            $msg = 'Kode kriteria sudah digunakan, silakan gunakan kode lain.';
        } else {
            $msg = 'Gagal menyimpan data: ' . $e->getMessage();
        }
        $msgType = 'danger';
    }
}

// ====== SIMPAN BOBOT ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bobot'])) {
    $totalCek = 0;
    foreach ($_POST['bobot'] as $b) $totalCek += (float) $b;

    if (abs($totalCek - 1) > 0.001) {
        $msg = 'Gagal menyimpan, total bobot harus = 1.000 (saat ini ' . number_format($totalCek, 3) . ').';
        $msgType = 'danger';
    } else {
        try {
            $stmt = $koneksi->prepare("UPDATE kriteria SET bobot=? WHERE id=?");
            foreach ($_POST['bobot'] as $id => $bobot) {
                $b = (float) $bobot;
                $iid = (int) $id;
                $stmt->bind_param('di', $b, $iid);
                $stmt->execute();
            }
            $msg = 'Bobot kriteria berhasil disimpan.';
        } catch (mysqli_sql_exception $e) {
            $msg = 'Gagal menyimpan data: ' . $e->getMessage();
            $msgType = 'danger';
        }
    }
}

// ====== HAPUS ======
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $koneksi->prepare("DELETE FROM kriteria WHERE id = ?");
    $stmt->bind_param('i', $id);

    try {
        $stmt->execute();
        $msg = 'Data kriteria berhasil dihapus.';
        $msgType = 'success';
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1451) {
            $msg = 'Data tidak bisa dihapus karena masih digunakan pada perhitungan lain.';
        } else {
            $msg = 'Gagal menghapus data: ' . $e->getMessage();
        }
        $msgType = 'danger';
    }
}

$data = [];
$res = $koneksi->query("SELECT * FROM kriteria ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $data[] = $row;

$total = array_sum(array_column($data, 'bobot'));

$pageTitle = 'Kriteria & Bobot';
include '../includes/admin_header.php';
?>

<div class="page-title-bar">
  <h2 style="display:flex; align-items:center; gap:8px;"><i class="ti ti-ruler-2"></i> Data Kriteria &amp; Bobot Penilaian (C1–C9)</h2>
  <button class="btn btn-primary btn-sm" onclick="openModalTambah()"><i class="ti ti-plus"></i> Tambah Kriteria</button>
</div>

<?php if ($msg): ?>
  <div class="alert alert-<?= $msgType ?>"><?= $msg ?></div>
<?php endif; ?>

<div class="alert alert-info">
  <i class="ti ti-info-circle"></i> Total seluruh bobot harus sama dengan <strong>1.000</strong> agar perhitungan SPK valid.
</div>

<div class="table-wrapper">
  <form method="POST" id="bobotForm">
    <table>
      <thead>
        <tr><th>Kode</th><th>Nama Kriteria</th><th>Tipe</th><th>Bobot</th><th>Keterangan</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($data)): ?>
          <tr><td colspan="6" style="text-align:center; padding:30px;">Belum ada data kriteria.</td></tr>
        <?php endif; ?>
        <?php foreach ($data as $d): ?>
        <tr>
          <td><span class="badge badge-blue"><?= htmlspecialchars($d['kode']) ?></span></td>
          <td><strong><?= htmlspecialchars($d['nama_kriteria']) ?></strong></td>
          <td>
            <span class="badge <?= $d['tipe'] === 'benefit' ? 'badge-green' : 'badge-yellow' ?>">
              <?= ucfirst($d['tipe']) ?>
            </span>
          </td>
          <td>
            <input type="number" step="0.001" min="0" max="1" class="form-control bobot-input"
                   name="bobot[<?= $d['id'] ?>]" value="<?= number_format($d['bobot'], 3) ?>"
                   style="width:100px;">
          </td>
          <td style="max-width:280px;"><?= htmlspecialchars($d['keterangan']) ?></td>
          <td>
            <div class="action-icons">
              <button type="button" class="edit" title="Edit" onclick='isiForm(<?= json_encode($d) ?>)'><i class="ti ti-pencil"></i></button>
              <a class="delete" title="Hapus" href="?hapus=<?= $d['id'] ?>" onclick="return confirm('Yakin ingin menghapus kriteria ini?')"><i class="ti ti-trash"></i></a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="weight-total <?= abs($total - 1) < 0.001 ? 'ok' : 'bad' ?>" id="kriteriaWeightTotal" style="margin-top:16px; padding:10px 14px; border-radius:6px; font-weight:600;">
      Total Bobot: <span id="kriteriaTotalValue"><?= number_format($total, 3) ?></span>
      <span id="kriteriaTotalStatus">
        <?php if (abs($total - 1) < 0.001): ?>
          <i class="ti ti-circle-check"></i> (Valid, total = 1.000)
        <?php else: ?>
          <i class="ti ti-alert-triangle"></i> (Total harus = 1.000)
        <?php endif; ?>
      </span>
    </div>

    <button type="submit" name="bobot_submit" class="btn btn-primary" style="margin-top:18px;"><i class="ti ti-device-floppy"></i> Simpan Bobot</button>
  </form>
</div>

<!-- MODAL TAMBAH/EDIT KRITERIA (di luar form bobot, form terpisah) -->
<div class="modal-overlay" id="modalEdit">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modalEdit')"><i class="ti ti-x"></i></button>
    <h3 id="modalTitle">Tambah Kriteria</h3>
    <form method="POST">
      <input type="hidden" name="id" id="f_id" value="0">

      <div class="form-group">
        <label>Kode</label>
        <input type="text" name="kode" id="f_kode" class="form-control" placeholder="C1" required>
      </div>

      <div class="form-group">
        <label>Nama Kriteria</label>
        <input type="text" name="nama_kriteria" id="f_nama_kriteria" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Tipe</label>
        <select name="tipe" id="f_tipe" class="form-control">
          <option value="benefit">Benefit</option>
          <option value="cost">Cost</option>
        </select>
      </div>

      <div class="form-group">
        <label>Keterangan</label>
        <textarea name="keterangan" id="f_keterangan" class="form-control"></textarea>
      </div>

      <p style="font-size:13px; color:#888; margin-top:-4px;">
        Bobot untuk kriteria ini diatur setelah disimpan, langsung di kolom Bobot pada tabel.
      </p>

      <button type="submit" name="simpan" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Simpan</button>
    </form>
  </div>
</div>

<style>
.weight-total.ok { background:#e6f7ec; color:#1e7e34; border:1px solid #b6e6c5; }
.weight-total.bad { background:#fdecea; color:#b71c1c; border:1px solid #f5c6cb; }
</style>

<script>
function updateKriteriaBobotTotal() {
  const bobotForm = document.getElementById('bobotForm');
  if (!bobotForm) return;

  const inputs = bobotForm.querySelectorAll('.bobot-input');
  let total = 0;
  inputs.forEach(el => total += (parseFloat(el.value) || 0));

  const box = bobotForm.querySelector('#kriteriaWeightTotal');
  const totalValue = bobotForm.querySelector('#kriteriaTotalValue');
  const totalStatus = bobotForm.querySelector('#kriteriaTotalStatus');
  if (!box || !totalValue || !totalStatus) return;

  const isValid = Math.abs(total - 1) < 0.001;

  totalValue.textContent = total.toFixed(3);
  box.classList.toggle('ok', isValid);
  box.classList.toggle('bad', !isValid);
  totalStatus.innerHTML = isValid
    ? '<i class="ti ti-circle-check"></i> (Valid, total = 1.000)'
    : '<i class="ti ti-alert-triangle"></i> (Total harus = 1.000)';
}

document.addEventListener('DOMContentLoaded', function () {
  const bobotForm = document.getElementById('bobotForm');
  if (bobotForm) {
    // Delegasi event: tetap jalan walau input ditambah/diubah secara dinamis
    bobotForm.addEventListener('input', function (e) {
      if (e.target.classList.contains('bobot-input')) {
        updateKriteriaBobotTotal();
      }
    });
  }
  // Sinkronkan total begitu halaman dimuat, bukan cuma andalkan nilai dari PHP
  updateKriteriaBobotTotal();
});

function isiForm(d) {
  document.getElementById('modalTitle').textContent = 'Edit Kriteria ' + d.kode + ' - ' + d.nama_kriteria;
  document.getElementById('f_id').value = d.id;
  document.getElementById('f_kode').value = d.kode;
  document.getElementById('f_nama_kriteria').value = d.nama_kriteria;
  document.getElementById('f_tipe').value = d.tipe;
  document.getElementById('f_keterangan').value = d.keterangan;
  openModal('modalEdit');
}

function openModalTambah() {
  document.getElementById('modalTitle').textContent = 'Tambah Kriteria';
  document.getElementById('f_id').value = 0;
  document.getElementById('f_kode').value = '';
  document.getElementById('f_nama_kriteria').value = '';
  document.getElementById('f_tipe').value = 'benefit';
  document.getElementById('f_keterangan').value = '';
  openModal('modalEdit');
}
</script>

<?php include '../includes/admin_footer.php'; ?>