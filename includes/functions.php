<?php
/* =========================================================
   1. RULE-BASED FILTERING BERDASARKAN JENIS KULIT
   ========================================================= */
function filterByJenisKulit($koneksi, $kode_kulit = null) {
    $produk = [];
    $result = $koneksi->query("SELECT * FROM sunscreen");
    while ($row = $result->fetch_assoc()) $produk[] = $row;
    return $produk;
}

function cekCocokJenisKulit($produk, $jk) {
    if (empty($jk['prioritas_sunscreen'])) return true; // fallback aman: dianggap cocok

    $prioritas = array_map('trim', explode(',', $jk['prioritas_sunscreen']));

    if (in_array('Semua', $prioritas, true)) return true;

    $jenisValid = ['Physical', 'Chemical', 'Hybrid'];
    $jenisMatch = array_intersect($prioritas, $jenisValid);

    if (in_array($produk['jenis_sunscreen'], $jenisMatch, true)) return true;

    return false;
}

function jenisKulitCocokUntukProduk($produk, $daftar_jenis_kulit) {
    $cocok = [];
    foreach ($daftar_jenis_kulit as $jk) {
        if (cekCocokJenisKulit($produk, $jk)) {
            $cocok[] = $jk;
        }
    }
    return $cocok;
}

/* =========================================================
   2. KONVERSI NILAI ATRIBUT KE BENTUK NUMERIK (MATRIKS)
   ========================================================= */
function skorSPF($spf) {
    $spf = (float) $spf;
    if ($spf > 70) return 5;
    if ($spf >= 50) return 4;
    if ($spf >= 30) return 3;
    if ($spf >= 15) return 2;
    return 1;
}

function skorPA($pa) {
    $pa = trim((string) $pa);
    if ($pa === 'PA++++') return 5;
    if ($pa === 'PA+++' || stripos($pa, 'UVA Protection') !== false) return 4;
    if ($pa === 'PA++') return 3;
    if ($pa === 'PA+') return 2;
    return 1; // kosong / tidak dikenali dianggap "tidak ada informasi PA"
}

function skorKandunganFA($kandungan_fa) {
    $map = [
        'bebas_semua'      => 5,
        'bebas_sebagian'   => 3,
        'mengandung_semua' => 1,
    ];
    return $map[$kandungan_fa] ?? 3;
}

function skorHarga($harga) {
    $harga = (float) $harga;
    if ($harga <= 50000) return 5;
    if ($harga <= 75000) return 4;
    if ($harga <= 100000) return 3;
    if ($harga <= 150000) return 2;
    return 1;
}

function skorUkuran($ukuran) {
    $ukuran = (float) $ukuran;
    if ($ukuran >= 100) return 5;
    if ($ukuran >= 80) return 4;
    if ($ukuran >= 60) return 3;
    if ($ukuran >= 40) return 2;
    return 1;
}

function buatMatriksKeputusan($daftar_produk, $kode_kulit = null) {
    $matriks = [];
    foreach ($daftar_produk as $p) {
        $matriks[$p['id']] = [
            'C1' => (float) skorSPF($p['spf']),
            'C2' => (float) skorPA($p['pa']),
            'C3' => (float) skorKandunganFA($p['kandungan_fa']),
            'C4' => $p['hypoallergenic'] === 'Ya' ? 5.0 : 1.0,
            'C5' => $p['water_resistant'] === 'Ya' ? 5.0 : 1.0,
            'C6' => (float) skorHarga($p['harga']),
            'C7' => (float) skorUkuran($p['ukuran']),
        ];
    }
    return $matriks;
}

/* =========================================================
   3. PERHITUNGAN SPK - METODE TOPSIS
   ========================================================= */
function normalisasiTopsis($matriks, $kriteria) {
    $normalisasi = [];
    $penyebut = [];

    foreach ($kriteria as $k) {
        $kode = $k['kode'];
        $kolom = array_column($matriks, $kode);
        $sumSq = 0;
        foreach ($kolom as $val) $sumSq += $val * $val;
        $penyebut[$kode] = sqrt($sumSq);
    }

    foreach ($matriks as $id => $row) {
        foreach ($kriteria as $k) {
            $kode = $k['kode'];
            $normalisasi[$id][$kode] = $penyebut[$kode] != 0
                ? $row[$kode] / $penyebut[$kode] : 0;
        }
    }

    return ['normalisasi' => $normalisasi, 'penyebut' => $penyebut];
}

function pembobotanTopsis($normalisasi, $kriteria) {
    $terbobot = [];
    foreach ($normalisasi as $id => $row) {
        foreach ($kriteria as $k) {
            $kode = $k['kode'];
            $terbobot[$id][$kode] = $row[$kode] * (float) $k['bobot'];
        }
    }
    return $terbobot;
}

function solusiIdealTopsis($terbobot, $kriteria) {
    $idealPositif = [];
    $idealNegatif = [];

    foreach ($kriteria as $k) {
        $kode = $k['kode'];
        $kolom = array_column($terbobot, $kode);

        if ($k['tipe'] === 'benefit') {
            $idealPositif[$kode] = max($kolom);
            $idealNegatif[$kode] = min($kolom);
        } else { // cost
            $idealPositif[$kode] = min($kolom);
            $idealNegatif[$kode] = max($kolom);
        }
    }

    return ['positif' => $idealPositif, 'negatif' => $idealNegatif];
}

function jarakIdealTopsis($terbobot, $idealPositif, $idealNegatif, $kriteria) {
    $dPositif = [];
    $dNegatif = [];

    foreach ($terbobot as $id => $row) {
        $sumPos = 0;
        $sumNeg = 0;
        foreach ($kriteria as $k) {
            $kode = $k['kode'];
            $sumPos += pow($row[$kode] - $idealPositif[$kode], 2);
            $sumNeg += pow($row[$kode] - $idealNegatif[$kode], 2);
        }
        $dPositif[$id] = sqrt($sumPos);
        $dNegatif[$id] = sqrt($sumNeg);
    }

    return ['positif' => $dPositif, 'negatif' => $dNegatif];
}

function nilaiPreferensiTopsis($dPositif, $dNegatif) {
    $V = [];
    foreach ($dPositif as $id => $dp) {
        $dn = $dNegatif[$id];
        $total = $dp + $dn;
        $V[$id] = $total != 0 ? $dn / $total : 0;
    }
    return $V;
}

function prosesSPK($koneksi, $kode_kulit) {
    $kriteria = [];
    $res = $koneksi->query("SELECT * FROM kriteria ORDER BY id ASC");
    while ($row = $res->fetch_assoc()) $kriteria[] = $row;

    $totalBobot = array_sum(array_column($kriteria, 'bobot'));
    if ($totalBobot > 0 && abs($totalBobot - 1) > 0.001) {
        foreach ($kriteria as &$k) {
            $k['bobot'] = $k['bobot'] / $totalBobot;
        }
        unset($k);
    }

    $daftar_produk = filterByJenisKulit($koneksi, $kode_kulit);
    $matriks = buatMatriksKeputusan($daftar_produk, $kode_kulit);

    $hasilNormalisasi = normalisasiTopsis($matriks, $kriteria);
    $normalisasi = $hasilNormalisasi['normalisasi'];
    $penyebut    = $hasilNormalisasi['penyebut'];

    $terbobot = pembobotanTopsis($normalisasi, $kriteria);

    $idealSolusi  = solusiIdealTopsis($terbobot, $kriteria);
    $idealPositif = $idealSolusi['positif'];
    $idealNegatif = $idealSolusi['negatif'];

    $jarak    = jarakIdealTopsis($terbobot, $idealPositif, $idealNegatif, $kriteria);
    $dPositif = $jarak['positif'];
    $dNegatif = $jarak['negatif'];

    $nilai_akhir = nilaiPreferensiTopsis($dPositif, $dNegatif);
    arsort($nilai_akhir);

    $ranking = [];
    $rank = 1;
    foreach ($nilai_akhir as $id => $skor) {
        $ranking[] = ['sunscreen_id' => $id, 'nilai_akhir' => $skor, 'ranking' => $rank];
        $rank++;
    }

    $produk_by_id = [];
    foreach ($daftar_produk as $p) $produk_by_id[$p['id']] = $p;

    return [
        'kriteria'      => $kriteria,
        'produk'        => $produk_by_id,
        'matriks'       => $matriks,
        'penyebut'      => $penyebut,
        'normalisasi'   => $normalisasi,
        'terbobot'      => $terbobot,
        'ideal_positif' => $idealPositif,
        'ideal_negatif' => $idealNegatif,
        'jarak_positif' => $dPositif,
        'jarak_negatif' => $dNegatif,
        'nilai_akhir'   => $nilai_akhir,
        'ranking'       => $ranking,
    ];
}

/* =========================================================
   4. GENERATE ALASAN REKOMENDASI (otomatis, deskriptif)
   ========================================================= */
function generateAlasan($produk, $kode_kulit) {
    $alasan = [];

    switch ($kode_kulit) {
        case 'sensitif':
            if ($produk['jenis_sunscreen'] === 'Physical') {
                $alasan[] = "Termasuk physical (mineral) sunscreen dengan risiko iritasi lebih rendah, cocok untuk kulit sensitif";
            }
            break;
        case 'kering':
            if ($produk['jenis_sunscreen'] === 'Hybrid') {
                $alasan[] = "Formula hybrid, memberikan kelembapan dan kenyamanan ekstra untuk kulit kering";
            } elseif ($produk['jenis_sunscreen'] === 'Physical') {
                $alasan[] = "Formula physical, nyaman digunakan untuk kulit kering";
            }
            break;
        case 'berminyak':
            if ($produk['jenis_sunscreen'] === 'Chemical') {
                $alasan[] = "Formula chemical bertekstur ringan, cocok untuk kulit berminyak";
            } elseif ($produk['jenis_sunscreen'] === 'Hybrid') {
                $alasan[] = "Formula hybrid bertekstur ringan, cocok untuk kulit berminyak";
            }
            break;
        case 'normal':
            $alasan[] = "Cocok digunakan untuk kulit normal";
            break;
    }

    if ((float) $produk['spf'] > 70) {
        $alasan[] = "SPF sangat tinggi (" . $produk['spf'] . ") untuk proteksi maksimal";
    } elseif ((float) $produk['spf'] >= 50) {
        $alasan[] = "SPF tinggi (" . $produk['spf'] . ") untuk proteksi optimal";
    }
    if (skorPA($produk['pa']) >= 4) $alasan[] = "Perlindungan UVA sangat baik (" . htmlspecialchars($produk['pa']) . ")";

    switch ($produk['kandungan_fa']) {
        case 'bebas_semua':
            $alasan[] = "Bebas fragrance dan alkohol, minim risiko iritasi untuk kulit anak";
            break;
        case 'bebas_sebagian':
            $alasan[] = "Bebas salah satu dari fragrance atau alkohol";
            break;
        case 'mengandung_semua':
            // tidak ditambahkan sebagai alasan positif
            break;
    }

    if ($produk['hypoallergenic'] === 'Ya') $alasan[] = "Sudah teruji hypoallergenic";
    if ($produk['water_resistant'] === 'Ya') $alasan[] = "Tahan air, cocok untuk aktivitas outdoor";

    if (empty($alasan)) $alasan[] = "Memiliki kombinasi nilai kriteria yang baik secara keseluruhan";

    return implode('. ', $alasan) . '.';
}

function iconJenisKulit($kode) {
    $map = [
        'sensitif'  => 'ti-shield-heart',
        'kering'    => 'ti-droplet-off',
        'berminyak' => 'ti-droplet',
        'normal'    => 'ti-mood-smile',
    ];
    return $map[$kode] ?? 'ti-sparkles';
}

function badgeJenisSunscreen($jenis) {
    $map = [
        'Physical'     => 'badge-jenis-physical',
        'Hybrid'       => 'badge-jenis-hybrid',
        'Chemical'     => 'badge-jenis-chemical',
    ];
    $class = $map[$jenis] ?? 'badge-jenis-chemical';
    return '<span class="badge-jenis ' . $class . '">' . htmlspecialchars($jenis) . '</span>';
}

/* =========================================================
   5. HELPER UMUM
   ========================================================= */
function rupiah($angka) {
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

/**
 * Menghasilkan badge medali berbentuk ikon Tabler (bukan emoji), dipakai
 */
function medali($ranking) {
    switch ($ranking) {
        case 1: return '<i class="ti ti-trophy" style="color:#FFD700;"></i>';
        case 2: return '<i class="ti ti-trophy" style="color:#C0C0C0;"></i>';
        case 3: return '<i class="ti ti-trophy" style="color:#CD7F32;"></i>';
        default: return '<i class="ti ti-hash"></i>';
    }
}

function fotoUrl($foto) {
    $file = !empty($foto) ? $foto : 'default.png';
    return BASE_URL . 'assets/img/produk/' . $file;
}