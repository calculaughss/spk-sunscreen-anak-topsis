<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

$msg = '';
$msgType = 'success';

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

$data = [];
$res = $koneksi->query("SELECT * FROM kriteria ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $data[] = $row;

$total = array_sum(array_column($data, 'bobot'));

$pageTitle = 'Bobot Kriteria';
include '../includes/admin_header.php';
?>

<div class="page-title-bar">
  <h2>⚖️ Bobot Kriteria</h2>
</div>

<?php if ($msg): ?><div class="alert alert-<?= $msgType ?>"><?= $msg ?></div><?php endif; ?>

<div class="alert alert-info">
  ℹ️ Atur bobot setiap kriteria (rentang 0 – 1). Total seluruh bobot harus sama dengan
  <strong>1.000</strong> agar perhitungan SPK valid.
</div>

<div class="table-wrapper">
  <form method="POST">
    <table>
      <thead>
        <tr><th>Kode</th><th>Nama Kriteria</th><th>Tipe</th><th>Bobot</th></tr>
      </thead>
      <tbody>
        <?php foreach ($data as $d): ?>
        <tr>
          <td><span class="badge badge-blue"><?= htmlspecialchars($d['kode']) ?></span></td>
          <td><?= htmlspecialchars($d['nama_kriteria']) ?></td>
          <td>
            <span class="badge <?= $d['tipe'] === 'benefit' ? 'badge-green' : 'badge-yellow' ?>">
              <?= ucfirst($d['tipe']) ?>
            </span>
          </td>
          <td>
            <input type="number" step="0.001" min="0" max="1" class="form-control"
                   name="bobot[<?= $d['id'] ?>]" value="<?= number_format($d['bobot'], 3) ?>"
                   oninput="updateWeightTotal()" style="width:100px;">
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="weight-total <?= abs($total - 1) < 0.001 ? 'ok' : 'bad' ?>" id="weightTotal" style="margin-top:16px; padding:10px 14px; border-radius:6px; font-weight:600;">
      Total Bobot: <span id="totalValue"><?= number_format($total, 3) ?></span>
      <span id="totalStatus"><?= abs($total - 1) < 0.001 ? '✅ (Valid, total = 1.000)' : '⚠️ (Total harus = 1.000)' ?></span>
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top:18px;">💾 Simpan Bobot</button>
  </form>
</div>

<style>
.weight-total.ok { background:#e6f7ec; color:#1e7e34; border:1px solid #b6e6c5; }
.weight-total.bad { background:#fdecea; color:#b71c1c; border:1px solid #f5c6cb; }
</style>

<script>
function updateWeightTotal() {
  const inputs = document.querySelectorAll('input[name^="bobot["]');
  let total = 0;
  inputs.forEach(el => total += parseFloat(el.value || 0));

  const box = document.getElementById('weightTotal');
  const totalValue = document.getElementById('totalValue');
  const totalStatus = document.getElementById('totalStatus');
  const isValid = Math.abs(total - 1) < 0.001;

  totalValue.textContent = total.toFixed(3);
  box.classList.toggle('ok', isValid);
  box.classList.toggle('bad', !isValid);
  totalStatus.textContent = isValid ? '✅ (Valid, total = 1.000)' : '⚠️ (Total harus = 1.000)';
}
</script>

<?php include '../includes/admin_footer.php'; ?>