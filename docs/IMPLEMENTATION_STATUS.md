# Status Implementasi (jujur)

**Environment pembuatan: tidak ada PHP/Composer, jadi kode BELUM dijalankan dan test BELUM dieksekusi.**
Jalankan sendiri: `composer install`, `php artisan migrate`, `npm i alpinejs @alpinejs/intersect @alpinejs/collapse`, `npm run build`, `php artisan test`.

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
Akun/registrasi Warga & dashboard warga, Web Push per kecamatan, data & poligon kecamatan + choropleth, CAPTCHA, hapus EXIF di server,
pHash, ekspor PDF, disposisi antar instansi, antrean offline, Lupa Password, halaman Tentang/Privasi, audit log viewer,
dokumen docs/ lainnya dari bagian 17 prompt, Lighthouse & uji responsif manual.
SEMUA kode belum dijalankan di environment pembuat (tanpa PHP); hasil test/build belum diketahui.
