-- =========================================================================
-- MIGRASI PEMBERSIHAN DATABASE - SSKids-DSS
-- =========================================================================
-- HANYA jalankan file ini jika database `spk_sunscreen2` Anda sudah pernah
-- di-import SEBELUMNYA (masih pakai struktur lama dengan tabel `users`,
-- kolom `konsultasi.user_id`, dan kolom `jenis_kulit.icon`).
--
-- Jika Anda melakukan import BARU/BERSIH dari file `spk_sunscreen2.sql`
-- (misalnya setelah DROP DATABASE), file ini TIDAK PERLU dijalankan sama
-- sekali, karena struktur di `spk_sunscreen2.sql` sudah otomatis bersih.
--
-- Cara pakai (phpMyAdmin): buka database spk_sunscreen2 > tab Import >
-- pilih file ini > Go.
--
-- Alasan pembersihan:
-- 1. Fitur login/akun pengguna sudah dihapus (SPK ini sederhana, tanpa
--    akun), sehingga tabel `users` dan kolom `konsultasi.user_id` sudah
--    tidak dipakai lagi oleh aplikasi.
-- 2. Kolom `jenis_kulit.icon` (emoji) sudah digantikan ikon Tabler yang
--    di-generate otomatis dari kolom `kode` (lihat fungsi iconJenisKulit()
--    di includes/functions.php), sehingga kolom ini sudah tidak dipakai.
-- =========================================================================

-- 1. Hapus foreign key & kolom user_id dari tabel konsultasi
ALTER TABLE `konsultasi` DROP FOREIGN KEY `konsultasi_ibfk_1`;
ALTER TABLE `konsultasi` DROP INDEX `user_id`;
ALTER TABLE `konsultasi` DROP COLUMN `user_id`;

-- 2. Hapus tabel users (sudah tidak dipakai sama sekali)
DROP TABLE IF EXISTS `users`;

-- 3. Hapus kolom icon dari tabel jenis_kulit
ALTER TABLE `jenis_kulit` DROP COLUMN `icon`;

-- Selesai. Struktur database Anda sekarang sudah sama dengan
-- spk_sunscreen2.sql versi terbaru.
