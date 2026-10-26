-- =========================================================================
-- UPDATE ATURAN JENIS KULIT & HAPUS TIPE "Gel/Oil-Free" - SSKids-DSS
-- =========================================================================
-- HANYA jalankan file ini jika database `spk_sunscreen2` Anda sudah pernah
-- di-import SEBELUMNYA dan belum memakai aturan rekomendasi jenis kulit
-- terbaru (tabel di bawah).
--
-- Jika Anda melakukan import BARU/BERSIH dari file `spk_sunscreen2.sql`,
-- file ini TIDAK PERLU dijalankan, karena datanya sudah otomatis sesuai.
--
-- Cara pakai (phpMyAdmin): buka database spk_sunscreen2 > tab Import >
-- pilih file ini > Go.
--
-- Aturan rekomendasi terbaru:
--   Sensitif  -> Physical
--   Kering    -> Physical, Hybrid
--   Berminyak -> Chemical, Hybrid
--   Normal    -> Physical, Chemical, Hybrid
--
-- Tipe sunscreen "Gel/Oil-Free" sudah tidak dipakai sama sekali dan
-- dihapus dari database (baik dari data produk maupun struktur kolom).
-- =========================================================================

-- 1. Jaga-jaga: kalau ada produk yang masih bertipe Gel/Oil-Free,
--    alihkan dulu ke Hybrid supaya tidak hilang datanya saat kolom
--    ENUM diperkecil di langkah berikutnya.
UPDATE `sunscreen` SET `jenis_sunscreen` = 'Hybrid' WHERE `jenis_sunscreen` = 'Gel/Oil-Free';

-- 2. Hapus opsi "Gel/Oil-Free" dari kolom jenis_sunscreen
ALTER TABLE `sunscreen` MODIFY `jenis_sunscreen` enum('Physical','Chemical','Hybrid') NOT NULL;

-- 3. Sesuaikan aturan rekomendasi tiap jenis kulit (memakai id supaya
--    tetap berhasil walau kode/nama sebelumnya masih "Kombinasi")
UPDATE `jenis_kulit` SET
  `nama_jenis_kulit` = 'Sensitif',
  `kode` = 'sensitif',
  `prioritas_sunscreen` = 'Physical',
  `deskripsi` = 'Mengandung zinc oxide atau titanium dioxide dengan risiko iritasi dan alergi kontak yang lebih rendah.'
WHERE `id` = 1;

UPDATE `jenis_kulit` SET
  `nama_jenis_kulit` = 'Normal',
  `kode` = 'normal',
  `prioritas_sunscreen` = 'Physical,Chemical,Hybrid',
  `deskripsi` = 'Tidak memiliki kebutuhan perlindungan khusus sehingga dapat menggunakan berbagai jenis sunscreen sesuai formulasi produk.'
WHERE `id` = 2;

UPDATE `jenis_kulit` SET
  `nama_jenis_kulit` = 'Kering',
  `kode` = 'kering',
  `prioritas_sunscreen` = 'Physical,Hybrid',
  `deskripsi` = 'Dapat dipilih sesuai formulasi produk yang memberikan kelembapan dan kenyamanan pada kulit.'
WHERE `id` = 3;

UPDATE `jenis_kulit` SET
  `nama_jenis_kulit` = 'Berminyak',
  `kode` = 'berminyak',
  `prioritas_sunscreen` = 'Chemical,Hybrid',
  `deskripsi` = 'Umumnya memiliki tekstur yang lebih ringan.'
WHERE `id` = 5;

-- Selesai. Struktur & data database Anda sekarang sudah sama dengan
-- spk_sunscreen2.sql versi terbaru.
--
-- Catatan: kalau Anda punya data konsultasi/hasil_rekomendasi lama dengan
-- teks "alasan" yang masih menyebut jenis kulit "Kombinasi" atau tipe
-- "Gel/Oil-Free", teks tersebut hanya catatan riwayat lama dan tidak
-- memengaruhi konsultasi baru (yang akan pakai aturan terbaru di atas).
