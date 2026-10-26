<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
$pageTitle = 'Beranda';
include 'includes/header.php';
include 'includes/navbar_user.php';

// Ambil data jenis kulit untuk section
$jenis_kulit = [];
$res = $koneksi->query("SELECT * FROM jenis_kulit ORDER BY id ASC");
while ($row = $res->fetch_assoc()) $jenis_kulit[] = $row;
?>

<!-- HERO SECTION -->
<section class="hero">
  <div class="container">
    <div class="hero-text">
      <span class="eyebrow" style="color:#fff; opacity:.85; display:inline-flex; align-items:center; gap:6px;">
        <i class="ti ti-sun"></i> SunScreen Kids Decision Support System (SSKids-DSS)
      </span>
      <h1>Sistem Pendukung Keputusan Pemilihan Sunscreen Anak Berdasarkan Jenis Kulit</h1>
      <p>Bingung memilih sunscreen yang tepat untuk si kecil? Sistem kami membantu Anda menemukan
         sunscreen terbaik sesuai jenis kulit anak, lengkap dengan perhitungan dan alasan ilmiah.</p>
      <div class="hero-actions">
        <a href="user/konsultasi.php" class="btn btn-primary"><i class="ti ti-bottle"></i> Mulai Konsultasi</a>
        <a href="user/rekomendasi.php" class="btn btn-outline" style="background:#fff;"><i class="ti ti-star"></i> Lihat Rekomendasi</a>
      </div>
    </div>
    <div class="hero-image">
      <!-- Ilustrasi anak bermain di bawah matahari menggunakan sunscreen -->
      <svg viewBox="0 0 420 380" xmlns="http://www.w3.org/2000/svg">
        <circle cx="330" cy="60" r="46" fill="#FFE38C"/>
        <g stroke="#FFE38C" stroke-width="6" stroke-linecap="round">
          <line x1="330" y1="0" x2="330" y2="-2"/>
          <line x1="395" y1="60" x2="410" y2="60"/>
          <line x1="378" y1="20" x2="388" y2="10"/>
          <line x1="378" y1="100" x2="388" y2="110"/>
        </g>
        <ellipse cx="210" cy="345" rx="190" ry="25" fill="#FFEDC2"/>
        <!-- Body -->
        <ellipse cx="180" cy="250" rx="60" ry="75" fill="#FFF4DA"/>
        <circle cx="180" cy="150" r="48" fill="#FFD9A0"/>
        <path d="M132 140 a48 48 0 0 1 96 0 v6 h-96z" fill="#5B4636"/>
        <circle cx="160" cy="155" r="5" fill="#2D3142"/>
        <circle cx="200" cy="155" r="5" fill="#2D3142"/>
        <path d="M165 172 q15 12 30 0" stroke="#2D3142" stroke-width="3" fill="none" stroke-linecap="round"/>
        <!-- Arms -->
        <path d="M130 220 q-40 10 -50 50" stroke="#FFD9A0" stroke-width="22" fill="none" stroke-linecap="round"/>
        <path d="M230 220 q40 10 55 40" stroke="#FFD9A0" stroke-width="22" fill="none" stroke-linecap="round"/>
        <!-- Sunscreen bottle in hand -->
        <rect x="255" y="225" width="34" height="54" rx="8" fill="#A8DADC"/>
        <rect x="263" y="215" width="18" height="14" rx="4" fill="#1D7A8C"/>
        <text x="260" y="258" font-size="10" fill="#1D7A8C" font-family="Arial" font-weight="bold">SPF</text>
        <!-- Sun cream dots on cheek -->
        <circle cx="155" cy="135" r="4" fill="#FFFFFF" opacity="0.8"/>
        <circle cx="205" cy="138" r="4" fill="#FFFFFF" opacity="0.8"/>
        <!-- Legs -->
        <rect x="155" y="300" width="20" height="45" rx="8" fill="#A8DADC"/>
        <rect x="195" y="300" width="20" height="45" rx="8" fill="#A8DADC"/>
        <!-- Beach umbrella -->
        <path d="M60 230 L60 340" stroke="#FF8C42" stroke-width="6"/>
        <path d="M0 230 a60 50 0 0 1 120 0 z" fill="#FF8C42"/>
        <path d="M0 230 a60 50 0 0 1 60 -50 a60 50 0 0 1 60 50 z" fill="#FFC93C" opacity="0.6"/>
      </svg>
    </div>
  </div>
</section>
<svg class="wave-divider" viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="margin-top:-6px;">
  <path fill="#FFF8E7" d="M0,32 C240,80 480,0 720,32 C960,64 1200,16 1440,48 L1440,80 L0,80 Z"/>
</svg>

<!-- CARA KERJA -->
<section class="section" id="cara-kerja">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Cara Kerja</span>
      <h2>Tiga Langkah Menuju Rekomendasi Terbaik</h2>
      <p>Proses sederhana berbasis data untuk membantu Anda memilih sunscreen yang tepat untuk anak.</p>
    </div>
    <div class="grid grid-3">
      <div class="card">
        <div class="icon-circle yellow"><i class="ti ti-clipboard-text"></i></div>
        <h4>1. Isi Data Anak</h4>
        <p style="color:var(--text-muted); font-size:14px;">Masukkan nama, umur, jenis kelamin, dan jenis kulit anak melalui form konsultasi singkat.</p>
      </div>
      <div class="card">
        <div class="icon-circle orange"><i class="ti ti-settings"></i></div>
        <h4>2. Sistem Memproses</h4>
        <p style="color:var(--text-muted); font-size:14px;">Sistem menyaring produk sesuai jenis kulit (rule-based), lalu menghitung skor menggunakan metode SPK (TOPSIS) berdasarkan 9 kriteria.</p>
      </div>
      <div class="card">
        <div class="icon-circle"><i class="ti ti-trophy"></i></div>
        <h4>3. Dapatkan Rekomendasi</h4>
        <p style="color:var(--text-muted); font-size:14px;">Lihat 3 sunscreen terbaik beserta skor akhir, detail produk, dan alasan rekomendasi.</p>
      </div>
    </div>
  </div>
</section>

<!-- JENIS KULIT -->
<section class="section section-alt" id="jenis-kulit">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Jenis Kulit</span>
      <h2>Kenali Jenis Kulit Anak Anda</h2>
      <p>Setiap jenis kulit memiliki kebutuhan sunscreen yang berbeda. Sistem akan menyesuaikan rekomendasi secara otomatis.</p>
    </div>

    <div class="skin-grid">
      <?php foreach ($jenis_kulit as $jk):
        $deskripsi_full  = trim($jk['deskripsi']);
        $deskripsi_short = mb_substr($deskripsi_full, 0, 60) . (mb_strlen($deskripsi_full) > 60 ? '...' : '');
        $perluDipotong   = mb_strlen($deskripsi_full) > 60;
      ?>
      <div class="card skin-card" onclick="toggleSkinCard(this)"
           data-full="<?= htmlspecialchars($deskripsi_full) ?>"
           data-short="<?= htmlspecialchars($deskripsi_short) ?>">
        <div class="icon-circle yellow"><i class="ti <?= iconJenisKulit($jk['kode']) ?>"></i></div>
        <h4><?= htmlspecialchars($jk['nama_jenis_kulit']) ?></h4>
        <p class="skin-desc"><?= htmlspecialchars($deskripsi_short) ?></p>
        <?php if ($perluDipotong): ?>
          <span class="skin-toggle-hint"><i class="ti ti-chevron-down"></i> <span class="hint-text">Selengkapnya</span></span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
/* Grid jenis kulit: center otomatis berapapun jumlah card-nya, tidak lagi
   terikat 5 kolom tetap seperti .grid-5 (yang bikin layout miring ke kiri
   kalau datanya kurang dari 5). */
.skin-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 24px;
}
.skin-grid .skin-card {
  flex: 0 1 220px;
  transition: all 0.25s ease;
}
.skin-card .skin-desc {
  transition: all 0.2s ease;
}
.skin-toggle-hint {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin-top: 8px;
  font-size: 12px;
  font-weight: 700;
  color: var(--sunset-orange);
}
.skin-toggle-hint .ti-chevron-down {
  transition: transform 0.2s ease;
}
.skin-card.expanded .skin-toggle-hint .ti-chevron-down {
  transform: rotate(180deg);
}
.skin-card.expanded {
  border-color: var(--sunset-orange);
  box-shadow: var(--shadow-soft);
}

@media (max-width: 600px) {
  .skin-grid .skin-card { flex: 1 1 100%; max-width: 320px; }
}
</style>

<!-- KEUNGGULAN -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow">Mengapa SSKids-DSS?</span>
      <h2>Dibangun untuk Kebutuhan Kulit Anak</h2>
    </div>
    <div class="grid grid-4">
      <div class="card">
        <div class="icon-circle"><i class="ti ti-chart-bar"></i></div>
        <h4>Akurat &amp; Terukur</h4>
        <p style="color:var(--text-muted); font-size:14px;">Rekomendasi berbasis 9 kriteria & metode SPK yang transparan.</p>
      </div>
      <div class="card">
        <div class="icon-circle yellow"><i class="ti ti-shield-check"></i></div>
        <h4>Aman untuk Anak</h4>
        <p style="color:var(--text-muted); font-size:14px;">Mempertimbangkan hypoallergenic, alkohol, dan fragrance.</p>
      </div>
      <div class="card">
        <div class="icon-circle orange"><i class="ti ti-target"></i></div>
        <h4>Rekomendasi Personal</h4>
        <p style="color:var(--text-muted); font-size:14px;">Hasil disesuaikan dengan jenis kulit unik setiap anak.</p>
      </div>
      <div class="card">
        <div class="icon-circle"><i class="ti ti-device-mobile"></i></div>
        <h4>Mudah Digunakan</h4>
        <p style="color:var(--text-muted); font-size:14px;">Tampilan responsif, ramah untuk diakses dari HP maupun desktop.</p>
      </div>
    </div>
  </div>
</section>

<script>
function toggleSkinCard(card) {
  const desc = card.querySelector('.skin-desc');
  const hintText = card.querySelector('.hint-text');
  const isExpanded = card.classList.contains('expanded');

  if (isExpanded) {
    desc.textContent = card.dataset.short;
    if (hintText) hintText.textContent = 'Selengkapnya';
    card.classList.remove('expanded');
  } else {
    desc.textContent = card.dataset.full;
    if (hintText) hintText.textContent = 'Tutup';
    card.classList.add('expanded');
  }
}
</script>

<?php include 'includes/footer.php'; ?>