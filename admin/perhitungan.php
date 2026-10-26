<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
cekLoginAdmin();

// Ambil jenis kulit untuk dipilih
$jenis_kulit_list = [];
$res = $koneksi->query("SELECT * FROM jenis_kulit ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $jenis_kulit_list[] = $row;

$kode_kulit = $_GET['jenis_kulit'] ?? 'normal';
$spk = prosesSPK($koneksi, $kode_kulit);

$kriteria = $spk['kriteria'];
$produk   = $spk['produk'];

// Data jenis kulit yang sedang dipilih (untuk panel info di bawah dropdown)
$jk_aktif = null;
foreach ($jenis_kulit_list as $jk) {
    if ($jk['kode'] === $kode_kulit) { $jk_aktif = $jk; break; }
}

$pageTitle = 'Perhitungan SPK';
include '../includes/admin_header.php';
?>

<style>
.badge-jenis {
  display: inline-block;
  padding: 2px 8px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  margin-left: 6px;
  white-space: nowrap;
}
.badge-jenis-physical { background:#e6f7ec; color:#1e7e34; border:1px solid #b6e6c5; }
.badge-jenis-hybrid   { background:#f1e9fb; color:#6f2ea6; border:1px solid #dcc7f0; }
.badge-jenis-chemical { background:#fdecea; color:#b71c1c; border:1px solid #f5c6cb; }

.legend-jenis {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  margin-top: 14px;
  padding-top: 14px;
  border-top: 1px solid #F0F0F0;
  font-size: 13px;
  color: var(--text-muted);
}
.legend-jenis span { display:flex; align-items:center; gap:6px; }
.legend-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }

.selector-row {
  display: flex;
  gap: 24px;
  flex-wrap: wrap;
  align-items: flex-end;
}
.jk-info-panel {
  flex: 1;
  min-width: 240px;
  background: var(--cream);
  border-radius: var(--radius-sm);
  padding: 12px 16px;
}
.jk-info-panel .jk-name {
  font-weight: 800;
  font-family: var(--font-heading);
  font-size: 15px;
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--deep-blue);
}

.info-note {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 12px;
  font-size: 13px;
  color: var(--text-muted);
}

.tab-btn { display: inline-flex; align-items: center; gap: 6px; }

/* Ranking badge (menggantikan fungsi medali() berbasis emoji) */
.rank-pill {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  min-width: 46px;
  padding: 4px 10px;
  border-radius: 999px;
  font-weight: 800;
  font-size: 13px;
}
.rank-pill.gold   { background: #FFF6DC; color: #8A6500; border: 1px solid var(--gold); }
.rank-pill.silver { background: #F3F3F3; color: #55575c; border: 1px solid var(--silver); }
.rank-pill.bronze { background: #FBEEE2; color: #8a4a15; border: 1px solid var(--bronze); }
.rank-pill.plain  { background: var(--cream); color: var(--text-muted); }

.pref-bar-wrap { display: flex; align-items: center; gap: 10px; min-width: 140px; }
.pref-bar {
  flex-grow: 1;
  height: 8px;
  background: #EFEFEF;
  border-radius: 999px;
  overflow: hidden;
}
.pref-bar-fill { height: 100%; background: var(--gradient-btn); border-radius: 999px; }
</style>

<div class="page-title-bar">
  <h2 style="display:flex; align-items:center; gap:8px;"><i class="ti ti-calculator"></i> Perhitungan SPK (Metode TOPSIS)</h2>
</div>



<?php if (empty($produk)): ?>
  <div class="empty-state card"><div class="emoji"><i class="ti ti-flask-off"></i></div><p>Tidak ada produk yang sesuai dengan filter jenis kulit ini.</p></div>
<?php else: ?>

<div class="tabs">
  <button class="tab-btn active" onclick="showTab('tabMatriks', this)"><i class="ti ti-table"></i> 1. Matriks Keputusan</button>
  <button class="tab-btn" onclick="showTab('tabNormalisasi', this)"><i class="ti ti-adjustments"></i> 2. Normalisasi</button>
  <button class="tab-btn" onclick="showTab('tabBobot', this)"><i class="ti ti-scale"></i> 3. Matriks Terbobot</button>
  <button class="tab-btn" onclick="showTab('tabIdeal', this)"><i class="ti ti-target-arrow"></i> 4. Solusi Ideal</button>
  <button class="tab-btn" onclick="showTab('tabJarak', this)"><i class="ti ti-ruler-measure"></i> 5. Jarak Ideal</button>
  <button class="tab-btn" onclick="showTab('tabRanking', this)"><i class="ti ti-trophy"></i> 6. Preferensi &amp; Ranking</button>
</div>

<!-- TAB 1: MATRIKS KEPUTUSAN -->
<div class="tab-content active" id="tabMatriks">
  <div class="card">
    <h3 style="margin-bottom:12px;">Matriks Keputusan (X)</h3>
    <p style="color:var(--text-muted); margin-bottom:12px; font-size:14px;">
      Nilai mentah hasil konversi atribut produk ke skala numerik C1–C7 untuk jenis kulit <strong><?= htmlspecialchars($kode_kulit) ?></strong>.
      Warna badge menandakan jenis sunscreen tiap produk.
    </p>
    <div class="table-wrapper" style="box-shadow:none;">
      <table>
        <thead><tr><th>Alternatif</th><?php foreach ($kriteria as $k): ?><th><?= $k['kode'] ?></th><?php endforeach; ?></tr></thead>
        <tbody>
          <?php foreach ($spk['matriks'] as $id => $row): ?>
          <tr>
            <td>
              <?= htmlspecialchars($produk[$id]['nama_produk']) ?>
              <?= badgeJenisSunscreen($produk[$id]['jenis_sunscreen']) ?>
            </td>
            <?php foreach ($kriteria as $k): ?><td><?= $row[$k['kode']] ?></td><?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr style="background:var(--cream); font-weight:700;">
            <td>Tipe Kriteria</td>
            <?php foreach ($kriteria as $k): ?>
              <td><span class="badge <?= $k['tipe']==='benefit' ? 'badge-green' : 'badge-yellow' ?>" style="font-size:11px;"><?= ucfirst($k['tipe']) ?></span></td>
            <?php endforeach; ?>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<!-- TAB 2: NORMALISASI -->
<div class="tab-content" id="tabNormalisasi">
  <div class="card">
    <h3 style="margin-bottom:12px;">Normalisasi Matriks (R) — Normalisasi Vektor</h3>
    <div class="formula-box">
      r(ij) = x(ij) / &radic;( &sum; x(ij)&sup2; )&nbsp;&nbsp;&nbsp;untuk setiap kolom kriteria j
    </div>
    <p style="color:var(--text-muted); margin-bottom:12px; font-size:14px;">
      Nilai pembagi (penyebut) untuk setiap kriteria:
      <?php foreach ($kriteria as $k): ?>
        <span class="badge badge-blue" style="margin-right:4px;"><?= $k['kode'] ?>=<?= number_format($spk['penyebut'][$k['kode']], 3) ?></span>
      <?php endforeach; ?>
    </p>
    <div class="table-wrapper" style="box-shadow:none;">
      <table>
        <thead><tr><th>Alternatif</th><?php foreach ($kriteria as $k): ?><th><?= $k['kode'] ?></th><?php endforeach; ?></tr></thead>
        <tbody>
          <?php foreach ($spk['normalisasi'] as $id => $row): ?>
          <tr>
            <td>
              <?= htmlspecialchars($produk[$id]['nama_produk']) ?>
              <?= badgeJenisSunscreen($produk[$id]['jenis_sunscreen']) ?>
            </td>
            <?php foreach ($kriteria as $k): ?><td><?= number_format($row[$k['kode']], 3) ?></td><?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- TAB 3: MATRIKS TERBOBOT -->
<div class="tab-content" id="tabBobot">
  <div class="card">
    <h3 style="margin-bottom:12px;">Matriks Ternormalisasi Terbobot (V)</h3>
    <div class="formula-box">v(ij) = w(j) &times; r(ij)</div>
    <p style="margin-bottom:12px; font-size:13px; color:var(--text-muted);">
      Bobot kriteria saat ini:
      <?php foreach ($kriteria as $k): ?>
        <span class="badge badge-blue" style="margin-right:4px;"><?= $k['kode'] ?>=<?= number_format($k['bobot'],3) ?></span>
      <?php endforeach; ?>
    </p>
    <div class="table-wrapper" style="box-shadow:none;">
      <table>
        <thead><tr><th>Alternatif</th><?php foreach ($kriteria as $k): ?><th><?= $k['kode'] ?></th><?php endforeach; ?></tr></thead>
        <tbody>
          <?php foreach ($spk['terbobot'] as $id => $row): ?>
          <tr>
            <td>
              <?= htmlspecialchars($produk[$id]['nama_produk']) ?>
              <?= badgeJenisSunscreen($produk[$id]['jenis_sunscreen']) ?>
            </td>
            <?php foreach ($kriteria as $k): ?><td><?= number_format($row[$k['kode']], 4) ?></td><?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- TAB 4: SOLUSI IDEAL -->
<div class="tab-content" id="tabIdeal">
  <div class="card">
    <h3 style="margin-bottom:12px;">Solusi Ideal Positif (A+) &amp; Negatif (A-)</h3>
    <div class="formula-box">
      Benefit&nbsp;: A+ = max(v_ij)&nbsp;&nbsp;|&nbsp;&nbsp;A- = min(v_ij)<br>
      Cost&nbsp;&nbsp;&nbsp;&nbsp;: A+ = min(v_ij)&nbsp;&nbsp;|&nbsp;&nbsp;A- = max(v_ij)
    </div>
    <div class="table-wrapper" style="box-shadow:none;">
      <table>
        <thead><tr><th>Solusi Ideal</th><?php foreach ($kriteria as $k): ?><th><?= $k['kode'] ?></th><?php endforeach; ?></tr></thead>
        <tbody>
          <tr>
            <td><strong>A+ (Ideal Positif)</strong></td>
            <?php foreach ($kriteria as $k): ?><td><?= number_format($spk['ideal_positif'][$k['kode']], 4) ?></td><?php endforeach; ?>
          </tr>
          <tr>
            <td><strong>A- (Ideal Negatif)</strong></td>
            <?php foreach ($kriteria as $k): ?><td><?= number_format($spk['ideal_negatif'][$k['kode']], 4) ?></td><?php endforeach; ?>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- TAB 5: JARAK IDEAL -->
<div class="tab-content" id="tabJarak">
  <div class="card">
    <h3 style="margin-bottom:12px;">Jarak Terhadap Solusi Ideal (D+ &amp; D-)</h3>
    <div class="formula-box">
      D+(i) = &radic;( &sum; (v_ij - A+_j)&sup2; )<br>
      D-(i) = &radic;( &sum; (v_ij - A-_j)&sup2; )
    </div>
    <div class="table-wrapper" style="box-shadow:none;">
      <table>
        <thead><tr><th>Alternatif</th><th>D+ (Jarak ke Ideal Positif)</th><th>D- (Jarak ke Ideal Negatif)</th></tr></thead>
        <tbody>
          <?php foreach ($spk['jarak_positif'] as $id => $dp): ?>
          <tr>
            <td>
              <?= htmlspecialchars($produk[$id]['nama_produk']) ?>
              <?= badgeJenisSunscreen($produk[$id]['jenis_sunscreen']) ?>
            </td>
            <td><?= number_format($dp, 5) ?></td>
            <td><?= number_format($spk['jarak_negatif'][$id], 5) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- TAB 6: NILAI PREFERENSI & RANKING -->
<div class="tab-content" id="tabRanking">
  <div class="card">
    <h3 style="margin-bottom:12px;">Nilai Preferensi (V) &amp; Hasil Ranking</h3>
    <div class="formula-box">V(i) = D-(i) / ( D+(i) + D-(i) )</div>
    <div class="table-wrapper" style="box-shadow:none;">
      <table>
        <thead><tr><th>Ranking</th><th>Alternatif</th><th>Jenis</th><th>Merek</th><th>D+</th><th>D-</th><th>Nilai Preferensi (V)</th></tr></thead>
        <tbody>
          <?php foreach ($spk['ranking'] as $r):
            $p = $produk[$r['sunscreen_id']];
            $id = $r['sunscreen_id'];
            $rankClass = $r['ranking'] === 1 ? 'gold' : ($r['ranking'] === 2 ? 'silver' : ($r['ranking'] === 3 ? 'bronze' : 'plain'));
            $rankIcon  = $r['ranking'] <= 3 ? 'ti-trophy' : 'ti-hash';
          ?>
          <tr>
            <td><span class="rank-pill <?= $rankClass ?>"><i class="ti <?= $rankIcon ?>"></i> #<?= $r['ranking'] ?></span></td>
            <td><strong><?= htmlspecialchars($p['nama_produk']) ?></strong></td>
            <td><?= badgeJenisSunscreen($p['jenis_sunscreen']) ?></td>
            <td><?= htmlspecialchars($p['merek']) ?></td>
            <td><?= number_format($spk['jarak_positif'][$id], 5) ?></td>
            <td><?= number_format($spk['jarak_negatif'][$id], 5) ?></td>
            <td>
              <div class="pref-bar-wrap">
                <strong><?= number_format($r['nilai_akhir'], 5) ?></strong>
                <div class="pref-bar"><div class="pref-bar-fill" style="width:<?= min(100, $r['nilai_akhir'] * 100) ?>%;"></div></div>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php endif; ?>

<?php include '../includes/admin_footer.php'; ?>