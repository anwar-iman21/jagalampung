# Status Implementasi (jujur)

**Validasi environment:** PHP 8.2, Composer 2.9, Laravel 12. Migrasi dan build frontend sudah dijalankan. `php artisan test` belum berjalan karena tes menggunakan Pest (`it()`), tetapi Pest tidak tercantum/terpasang pada dependency proyek.

## Sudah ditulis
Landing page animasi (hero, cara kerja, kategori, count-up dari DB, FAQ, CTA), navbar kaca + progres scroll, tema gelap,
motion system (reveal, spotlight, hover-lift, reduced-motion), Lapor Cepat (geolokasi, kompres foto klien, honeypot, rate limit,
idempotensi, skor kepercayaan), konfirmasi + kode lacak/token, Lacak Laporan, state machine status, test unit skor/haversine/status.

## Belum ada (fitur lanjutan)
Auth & RBAC, dashboard admin/super admin, peta Leaflet, info darurat dari sumber resmi, Web Push, statistik/ekspor,
CAPTCHA, hapus EXIF server-side (re-encode GD), pHash, blocklist, audit log, test feature.

## Tahap 2 (ditambahkan)
Login admin (rate limit), middleware EnsureRole (server-side), antrean laporan + filter + paginasi stabil, detail laporan
(skor + alasan), verifikasi/ubah status dengan state machine + lockForUpdate + riwayat + audit log, peta publik (Leaflet CDN,
hanya laporan valid, koordinat diperhalus), seeder Super Admin dari .env (ADMIN_EMAIL, ADMIN_PASSWORD), test feature akses.
Tetap BELUM dijalankan (tidak ada PHP di environment pembuat). Catatan: Admin Instansi lintas-instansi belum dibatasi
(belum ada tabel instansi), jadi semua admin melihat semua laporan.

## Tahap 3 (ditambahkan)
Instansi (`agencies`) + penugasan laporan otomatis per kategori; pembatasan Admin Instansi lewat `Report::visibleTo` (404 lintas instansi);
manajemen Admin Instansi (Super Admin); info darurat publik + kelola (kontak, titik evakuasi, peringatan manual); statistik
(Chart.js, rata-rata/median respons, % spam, 30 hari WIB) + ekspor CSV (anti CSV-injection, tanpa kontak); blocklist + riwayat perangkat di skor.
Test baru: StatisticsTest, ScopingAndBlocklistTest.

## Masih belum ada (jujur)
Akun/registrasi dan dashboard Warga, validasi NIK 16 digit (terenkripsi), serta ekspor PDF sudah tersedia.
Pemulihan/lupa kata sandi sengaja belum disediakan. Fitur lanjutan yang masih belum ada: Web Push per kecamatan,
data & poligon kecamatan + choropleth, CAPTCHA, hapus EXIF di server, pHash, disposisi antar instansi, antrean offline,
halaman Tentang/Privasi, audit log viewer, dan Lighthouse/uji responsif manual.
