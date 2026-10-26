<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

$msg = '';

// Edit data user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id = (int) $_POST['id'];
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);
    $status = $_POST['status'];

    $stmt = $koneksi->prepare("UPDATE users SET nama=?, email=?, no_hp=?, status=? WHERE id=?");
    $stmt->bind_param('ssssi', $nama, $email, $no_hp, $status, $id);
    $stmt->execute();

    // Reset password jika diisi
    if (!empty($_POST['password_baru'])) {
        $hash = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
        $stmt2 = $koneksi->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt2->bind_param('si', $hash, $id);
        $stmt2->execute();
    }
    $msg = 'Data user berhasil diperbarui.';
}

// Hapus user
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $koneksi->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $msg = 'User berhasil dihapus.';
}

$data = [];
$res = $koneksi->query("SELECT * FROM users ORDER BY created_at DESC");
while ($row = $res->fetch_assoc()) $data[] = $row;

$pageTitle = 'Kelola User';
include '../includes/admin_header.php';
?>

<div class="page-title-bar">
  <h2 style="display:flex; align-items:center; gap:8px;"><i class="ti ti-users"></i>  Kelola User (Orang Tua)</h2>
</div>

<?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>

<div class="table-wrapper">
  <table>
    <thead>
      <tr><th>Nama</th><th>Email</th><th>No HP</th><th>Tanggal Daftar</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="6" style="text-align:center; padding:30px;">Belum ada user terdaftar.</td></tr>
      <?php endif; ?>
      <?php foreach ($data as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['nama']) ?></td>
        <td><?= htmlspecialchars($d['email']) ?></td>
        <td><?= htmlspecialchars($d['no_hp']) ?></td>
        <td><?= date('d M Y', strtotime($d['created_at'])) ?></td>
        <td><span class="badge <?= $d['status']==='aktif' ? 'badge-green' : 'badge-yellow' ?>"><?= ucfirst($d['status']) ?></span></td>
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

<div class="modal-overlay" id="modalEdit">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal('modalEdit')">✕</button>
    <h3>Edit Data User</h3>
    <form method="POST">
      <input type="hidden" name="id" id="f_id">
      <div class="form-group"><label>Nama</label><input type="text" name="nama" id="f_nama" class="form-control" required></div>
      <div class="form-group"><label>Email</label><input type="email" name="email" id="f_email" class="form-control" required></div>
      <div class="form-group"><label>No HP</label><input type="text" name="no_hp" id="f_no_hp" class="form-control"></div>
      <div class="form-group">
        <label>Status</label>
        <select name="status" id="f_status" class="form-control">
          <option value="aktif">Aktif</option>
          <option value="nonaktif">Nonaktif</option>
        </select>
      </div>
      <div class="form-group"><label>Reset Password (opsional)</label><input type="password" name="password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah"></div>
      <button type="submit" name="simpan" class="btn btn-primary btn-block">Simpan</button>
    </form>
  </div>
</div>

<script>
function isiForm(d) {
  document.getElementById('f_id').value = d.id;
  document.getElementById('f_nama').value = d.nama;
  document.getElementById('f_email').value = d.email;
  document.getElementById('f_no_hp').value = d.no_hp;
  document.getElementById('f_status').value = d.status;
  openModal('modalEdit');
}
</script>

<?php include '../includes/admin_footer.php'; ?>
