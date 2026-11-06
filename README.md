# JagaLampung — paket timpa (overlay)

Timpa isi folder ini ke root project Laravel Anda (struktur folder sama: app, config, database, resources, routes, tests, docs).

## Setup
```bash
npm i alpinejs @alpinejs/intersect @alpinejs/collapse
# .env: isi ADMIN_EMAIL, ADMIN_PASSWORD, dan database
php artisan migrate
php artisan db:seed --class=JagaLampungSeeder
php artisan storage:link
npm run build        # atau: npm run dev
php artisan serve
php artisan test
```
CSS ditulis untuk Tailwind v4 (default Laravel 12). Untuk Tailwind v3, ganti baris awal `resources/css/app.css` dan pindahkan token `@theme` ke `tailwind.config.js`.
Test memakai Pest; bila project Anda PHPUnit, jalankan `composer require pestphp/pest --dev` atau konversi sintaks test.

## Halaman
Publik: `/` `/lapor` `/lacak` `/peta` `/darurat` — Admin: `/masuk` lalu `/admin/laporan`, `/admin/statistik`, `/admin/darurat`, `/admin/pengguna` (Super Admin).

Lihat `docs/` untuk desain anti-spam, sumber data, dan status implementasi.
