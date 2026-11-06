# JagaLampung

🌱 **Platform pelaporan masalah dan informasi kebencanaan untuk masyarakat Lampung.**

JagaLampung membantu warga mengirim laporan lapangan dengan foto dan lokasi, menyimpan kode untuk melacak tindak lanjut, serta memberi admin alat untuk memverifikasi dan mengelola laporan. Laporan yang sudah diverifikasi dapat ditampilkan di peta publik, sedangkan informasi penting terkait keadaan darurat tersedia dalam satu halaman.

> **Penting:** repositori ini merupakan paket *overlay* untuk proyek Laravel 12, bukan arsip aplikasi Laravel yang berdiri sendiri. Salin atau gabungkan isinya ke root proyek Laravel yang sesuai. Selalu tinjau perubahan dan cadangkan file proyek sebelum menimpa file bernama sama.

## ✨ Yang membuat JagaLampung berguna

- **Pelaporan cepat tanpa akun warga.** Warga dapat mengirim laporan tanpa registrasi atau login.
- **Bukti dan lokasi dalam satu alur.** Formulir menerima foto, koordinat, kategori, deskripsi, dan kontak opsional.
- **Bisa dipantau secara privat.** Setiap laporan mendapat kode lacak dan token rahasia; keduanya diperlukan untuk melihat status.
- **Prioritas untuk kejadian darurat.** Laporan kecelakaan dan bencana diprioritaskan agar tidak tenggelam oleh skor kepercayaan yang rendah.
- **Peta publik yang lebih berhati-hati terhadap privasi.** Hanya laporan berstatus verifikasi valid yang dikirim ke peta; koordinat dibulatkan dan kontak maupun foto tidak disertakan.
- **Pembagian kerja per instansi.** Laporan dapat ditugaskan berdasarkan kategori, dan Admin Instansi hanya melihat laporan instansinya.
- **Moderasi yang berlapis.** Sinyal anti-spam membantu admin meninjau laporan tanpa otomatis menolak laporan yang mencurigakan.
- **Informasi darurat yang dapat dikelola.** Admin dapat mengatur kontak, titik evakuasi, dan peringatan manual.
- **Rekap untuk pengambilan keputusan.** Statistik mencakup tren laporan, kategori, status, dan waktu respons; CSV disediakan tanpa data kontak pelapor.
- **Tampilan responsif dan interaktif.** Antarmuka menggunakan Blade, Tailwind CSS v4, dan Alpine.js.

## 🧭 Halaman dan peran

### Untuk publik

| Halaman | Alamat | Kegunaan |
|---|---|---|
| Beranda | `/` | Ringkasan layanan, kategori laporan, cara kerja, dan akses cepat |
| Lapor | `/lapor` | Mengirim laporan baru dengan foto serta lokasi |
| Selesai melapor | `/lapor/selesai/{kode}` | Melihat konfirmasi setelah pengiriman |
| Lacak | `/lacak` | Memeriksa status menggunakan kode dan token |
| Peta | `/peta` | Melihat laporan yang sudah diverifikasi valid |
| Darurat | `/darurat` | Melihat nomor penting, peringatan, dan titik evakuasi |

Kategori laporan yang tersedia: kecelakaan/darurat, jalan rusak, bencana, kriminalitas, sampah, dan lainnya.

### Untuk admin

Masuk melalui `/masuk`.

| Halaman | Akses | Kegunaan |
|---|---|---|
| `/admin/laporan` | Admin Instansi dan Super Admin | Antrean, pencarian kode, serta filter kategori, verifikasi, dan status |
| `/admin/laporan/{id}` | Admin Instansi yang berwenang dan Super Admin | Meninjau laporan, mengubah verifikasi/status, dan menambahkan catatan |
| `/admin/statistik` | Admin Instansi dan Super Admin | Statistik laporan dan unduhan CSV |
| `/admin/darurat` | Admin Instansi dan Super Admin | Mengelola kontak, titik evakuasi, dan peringatan manual |
| `/admin/pengguna` | Super Admin | Membuat atau menonaktifkan akun Admin Instansi |

**Admin Instansi** dibatasi ke laporan yang ditugaskan kepada instansinya. **Super Admin** dapat melihat seluruh laporan dan mengelola akun admin. Hak akses diterapkan di sisi server, bukan hanya dengan menyembunyikan menu.

## 🔄 Alur laporan

1. Warga membuka **Lapor**, memilih kategori, mengambil atau memilih foto, lalu mengirim lokasi dan informasi pendukung.
2. Sistem memvalidasi masukan, menerapkan pembatasan pengiriman, menghitung skor kepercayaan, lalu menyimpan laporan.
3. Warga menerima **kode lacak dan token rahasia**. Simpan keduanya; token diperlukan untuk menjaga informasi laporan tetap privat.
4. Admin meninjau laporan, menetapkan verifikasi, lalu memperbarui status sesuai alur yang diizinkan.
5. Warga memasukkan kode dan token pada **Lacak** untuk memeriksa perkembangan. Hanya laporan yang ditandai valid yang ditampilkan pada peta publik.

JagaLampung bukan pengganti layanan darurat. Jika ada ancaman langsung terhadap nyawa atau keselamatan, hubungi layanan darurat terlebih dahulu.

## 🛡️ Moderasi, privasi, dan keamanan data

### Perlindungan spam dan duplikasi

Desainnya mengutamakan agar laporan asli tetap masuk untuk ditinjau manusia. Lapisan yang aktif meliputi:

- Batas pengiriman per hash alamat IP: **5 laporan per jam**.
- Honeypot, pemeriksaan waktu pengisian minimum, validasi gambar, dan pemeriksaan rentang koordinat kasar Lampung.
- Skor kepercayaan **0–100** beserta alasan sinyal untuk membantu proses peninjauan admin.
- Riwayat perangkat yang sebelumnya ditandai spam atau valid, serta blocklist perangkat sementara yang dapat diterapkan admin.
- Kunci idempotensi untuk mencegah pengiriman ulang permintaan yang sama membuat laporan ganda.
- Prioritas tinggi untuk kategori kecelakaan dan bencana.

Skor adalah alat bantu moderasi, bukan keputusan otomatis bahwa suatu laporan benar atau palsu. Admin tetap perlu memeriksa konteks dan bukti.

### Data pelapor dan peta

- Login tidak diperlukan untuk warga. Kode lacak dan token rahasia berfungsi sebagai kredensial untuk melihat status laporan.
- Kontak pelapor bersifat opsional dan dienkripsi oleh Laravel pada model laporan.
- IP dan identitas perangkat disimpan dalam bentuk hash untuk fungsi pembatasan dan moderasi, bukan sebagai teks biasa.
- Data peta publik hanya berisi kode, kategori, status, waktu, dan koordinat yang dibulatkan. Data kontak dan foto tidak dikirim sebagai data peta.
- Ekspor CSV tidak menyertakan kontak pelapor dan menerapkan perlindungan terhadap formula CSV berbahaya.

Tetapkan `APP_KEY` yang kuat dan rahasiakan file `.env`. Atur hak akses database dan penyimpanan foto sesuai kebijakan operasional Anda. Tinjau kebijakan privasi, retensi, serta kewajiban hukum sebelum menggunakan data warga di lingkungan produksi.

## 🧰 Teknologi dan prasyarat

- PHP **8.2 atau lebih baru**
- Laravel **12**
- Composer
- Node.js dan npm
- Database yang didukung oleh konfigurasi Laravel Anda
- Ekstensi PHP yang dibutuhkan Laravel dan driver database pilihan

Frontend menggunakan Vite, Tailwind CSS **v4**, dan Alpine.js beserta plugin Intersect dan Collapse. Kode tes menggunakan sintaks **Pest**. Pada manifest Composer yang disertakan, PHPUnit tersedia tetapi Pest belum tercantum sebagai dependensi; pasang Pest beserta integrasi Laravel atau konversikan tes sebelum menjalankannya.

## 🚀 Pemasangan ke proyek Laravel

### 1. Siapkan proyek dan cadangan

Pastikan target merupakan proyek Laravel 12, lalu buat cadangan atau commit kondisi proyek saat ini. Paket ini menambahkan atau menimpa file di `app/`, `config/`, `database/`, `resources/`, `routes/`, `tests/`, dan `docs/`. Periksa konflik dengan kode proyek Anda sebelum mengganti file yang sudah ada.

### 2. Gabungkan file overlay

Salin isi paket ke root Laravel, dengan mempertahankan struktur direktori. Jika proyek memiliki `routes/web.php`, `composer.json`, `package.json`, konfigurasi, atau migrasi sendiri, gabungkan perubahan dengan hati-hati alih-alih mengganti seluruh file secara membabi buta.

### 3. Pasang dependensi

Jalankan dari root proyek:

```bash
composer install
npm install
```

Jika direktori `vendor/` atau dependensi Node sudah dikelola oleh proyek target, tinjau perubahan manifest terlebih dahulu. Paket frontend Alpine.js, `@alpinejs/intersect`, dan `@alpinejs/collapse` sudah dicantumkan dalam `package.json` yang disertakan.

### 4. Siapkan konfigurasi lingkungan

Jika belum memiliki `.env`, buat dari `.env.example`. Pertahankan konfigurasi yang sudah ada, lalu isi konfigurasi aplikasi dan database. Tambahkan kredensial Super Admin:

```dotenv
APP_NAME="JagaLampung"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=ganti-dengan-password-kuat
```

Atur `DB_CONNECTION` dan variabel database lain sesuai driver yang digunakan. Jangan gunakan `APP_DEBUG=true` atau password contoh di produksi. Buat `APP_KEY` bila belum ada:

```bash
php artisan key:generate
```

`ADMIN_EMAIL` dan `ADMIN_PASSWORD` dibaca oleh seeder untuk membuat atau memperbarui akun Super Admin. Seeder akan melewati pembuatan akun bila salah satu variabel belum diisi.

### 5. Migrasikan database dan isi data awal

```bash
php artisan migrate
php artisan db:seed --class=JagaLampungSeeder
```

Seeder JagaLampung menambahkan daftar instansi awal, nomor 112, dan akun Super Admin dari `.env`. Data nomor 112 yang disiapkan **belum dilengkapi sumber dan tanggal verifikasi**; admin perlu memeriksa dan mencatat informasi resmi sebelum mengandalkannya. Titik evakuasi dan peringatan resmi juga tidak otomatis diisi.

### 6. Siapkan penyimpanan dan aset

```bash
php artisan storage:link
npm run build
```

Untuk pengembangan frontend dengan hot reload, gunakan `npm run dev`. Foto laporan disimpan melalui disk `public` Laravel; pastikan direktori penyimpanan dapat ditulis dan aturan akses/backup-nya sesuai kebutuhan deployment.

### 7. Jalankan aplikasi

```bash
php artisan serve
```

Buka alamat lokal yang ditampilkan Artisan, kemudian uji alur publik dan admin. Untuk lingkungan produksi, gunakan web server dan konfigurasi deployment Laravel yang sesuai, bukan server bawaan Artisan.

## 🧪 Menjalankan tes

Tes unit dan fitur berada di `tests/`. File tes memakai sintaks Pest, sementara dependensi Composer yang tersedia pada paket ini hanya mendeklarasikan PHPUnit. Sebelum menjalankan tes, pasang Pest dan plugin Laravel yang kompatibel dengan versi proyek, atau ubah tes ke PHPUnit.

```bash
php artisan test
```

Pastikan konfigurasi database testing tidak menunjuk ke database produksi. Tes mencakup antara lain transisi status, skor kepercayaan, pendeteksian duplikasi, statistik, pembatasan akses, dan blocklist. Status implementasi dan catatan validasi environment dapat dilihat di [`docs/IMPLEMENTATION_STATUS.md`](docs/IMPLEMENTATION_STATUS.md).

## 🎨 Catatan Tailwind

CSS paket ini menggunakan sintaks dan token tema **Tailwind CSS v4** yang sesuai dengan setup Laravel 12 modern. Jika proyek target masih menggunakan Tailwind v3, sesuaikan deklarasi awal di `resources/css/app.css` dan pindahkan token `@theme` ke konfigurasi `tailwind.config.js` sesuai setup proyek Anda.

## 📌 Batasan yang perlu diketahui

JagaLampung menyediakan fondasi aplikasi dan alur moderasi, tetapi beberapa integrasi operasional belum tersedia atau belum diverifikasi:

- **Belum ada integrasi feed BMKG**; peringatan yang ditambahkan admin adalah input manual.
- **Data resmi titik evakuasi dan batas/poligon kecamatan belum disediakan.**
- **CAPTCHA, pHash/klaster duplikasi otomatis pada alur kirim, dan penghapusan EXIF sisi server belum tersedia.** Service pendeteksi duplikasi ada, tetapi belum menjadi bagian aktif dari proses penerimaan laporan.
- **Belum ada akun atau dashboard warga, notifikasi Web Push, pelaporan offline, lupa password, atau ekspor PDF.**
- Data kontak darurat awal 112 memerlukan verifikasi dan pencatatan sumber. Pastikan kebenaran dan ketersediaan layanan di wilayah operasi Anda sebelum publikasi.
- Peta menggunakan tile OpenStreetMap melalui library/CDN; patuhi kebijakan penggunaan penyedia dan siapkan penyedia tile yang sesuai untuk trafik produksi.
- Statistik waktu respons bergantung pada riwayat perubahan status yang tercatat; metrik belum bermakna sebelum ada data operasional.
- Kode dan tes perlu dijalankan pada environment target Anda. Periksa [`docs/IMPLEMENTATION_STATUS.md`](docs/IMPLEMENTATION_STATUS.md) untuk catatan status dan verifikasi yang belum dilakukan.

## 📚 Dokumentasi

- [`docs/IMPLEMENTATION_STATUS.md`](docs/IMPLEMENTATION_STATUS.md) — status fitur, batasan, dan catatan verifikasi.
- [`docs/ANTI_SPAM_DESIGN.md`](docs/ANTI_SPAM_DESIGN.md) — prinsip dan lapisan anti-spam.
- [`docs/DATA_SOURCES.md`](docs/DATA_SOURCES.md) — sumber data, status verifikasi, dan kebutuhan data resmi.

## 🗂️ Struktur utama

| Direktori | Isi |
|---|---|
| `app/` | Controller, model, middleware, enum, dan service |
| `config/` | Konfigurasi aplikasi dan anti-spam |
| `database/` | Migrasi, seeder, dan factory |
| `resources/` | Tampilan Blade, CSS, dan JavaScript |
| `routes/` | Rute web dan console |
| `tests/` | Tes unit dan fitur |
| `docs/` | Dokumentasi desain, sumber data, dan status |

## 🤝 Pengembangan dan kontribusi

Sebelum mengirim perubahan, jalankan tes yang relevan dan uji alur yang terdampak pada proyek Laravel target. Untuk perubahan yang berkaitan dengan data warga, akses admin, peta, atau moderasi, jaga konsistensi validasi, otorisasi server, dan perlindungan privasi di semua jalur terkait.

---

**JagaLampung — lihat masalah, laporkan dengan bukti, pantau sampai ditangani.** 🌿
