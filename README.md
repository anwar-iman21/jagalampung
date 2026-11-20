# 🌿 JagaLampung

**Platform pelaporan warga dan informasi kebencanaan untuk Provinsi Lampung.**

JagaLampung membantu masyarakat melaporkan kejadian, menyertakan foto serta koordinat, lalu memantau perkembangan laporan melalui kode lacak singkat. Warga yang memiliki akun juga dapat melihat riwayat laporannya. Petugas mengelola laporan sesuai kewenangan, sementara informasi darurat dan laporan yang telah diverifikasi tersedia untuk publik.

> ℹ️ README ini berisi dokumentasi berbasis teks, ikon, dan tabel. Tidak ada screenshot yang digunakan.

## 📌 Daftar isi

| Bagian | Isi |
|---|---|
| [Fitur](#-fitur-utama) | Kemampuan aplikasi untuk warga, petugas, dan publik |
| [Alur penggunaan](#-alur-penggunaan) | Membuat, melacak, dan menangani laporan |
| [Halaman dan alamat](#-halaman-dan-alamat) | Daftar halaman publik, warga, dan admin |
| [Teknologi](#-teknologi) | Framework, frontend, dan prasyarat |
| [Instalasi](#-instalasi-dan-menjalankan-aplikasi) | Menyiapkan proyek dari awal |
| [Peran dan akses](#-peran-dan-hak-akses) | Batas kewenangan akun |
| [Privasi dan keamanan](#-privasi-keamanan-dan-moderasi) | Pengelolaan data dan perlindungan |
| [Pengujian](#-pengujian) | Build, tes, dan pemeriksaan |
| [Batasan](#-batasan-yang-perlu-diketahui) | Integrasi dan data yang belum tersedia |

## ✨ Fitur utama

| Fitur | Keterangan |
|---|---|
| 📝 Laporan publik | Warga dapat mengirim laporan tanpa harus membuat akun. Formulir menyediakan kategori, keterangan, foto, koordinat GPS, dan kontak opsional. |
| 📍 Pratinjau lokasi | Formulir menampilkan koordinat dan pratinjau peta saat lokasi GPS tersedia. Izin lokasi harus diberikan oleh pengguna. |
| 🔎 Kode lacak singkat | Setiap laporan memperoleh kode seperti `JL-XXXXXX`. Kode digunakan di halaman Lacak; tidak ada token rahasia terpisah. |
| 👤 Akun warga | Warga dapat mendaftar, masuk menggunakan NIK 16 digit, dan mengakses dashboard akun. |
| 🗂️ Riwayat dan detail warga | Dashboard menampilkan laporan yang terhubung dengan akun. Detail laporan hanya dapat dibaca dan tidak menyediakan fungsi edit. |
| 🧭 Peta laporan publik | Peta hanya menampilkan laporan yang sudah ditandai valid. Informasi kontak dan foto pelapor tidak disertakan pada data peta. |
| 🚨 Informasi darurat | Halaman publik menampilkan kontak penting, titik evakuasi, dan peringatan yang dikelola admin. |
| 🌦️ Prakiraan cuaca | Beranda meminta prakiraan tujuh hari dari Open-Meteo untuk koordinat Bandar Lampung. Data memerlukan koneksi internet dan dapat tidak tersedia sementara. |
| 🛡️ Moderasi laporan | Skor kepercayaan, sinyal anti-spam, pembatasan pengiriman, serta prioritas untuk laporan kecelakaan dan bencana membantu proses tinjauan petugas. |
| 🏢 Pembagian instansi | Laporan dapat ditugaskan ke instansi berdasarkan kategori. Admin Instansi hanya dapat melihat laporan sesuai instansinya. |
| 📊 Statistik dan CSV | Admin dapat melihat statistik laporan dan mengunduh rekap CSV. Ekspor tidak menyertakan kontak pelapor. |
| 📱 Antarmuka responsif | Tampilan dirancang untuk desktop dan ponsel, termasuk formulir, navigasi, dashboard, dan halaman admin. |

### Kategori laporan

| Kategori | Contoh penggunaan |
|---|---|
| 🚑 Kecelakaan / Darurat | Kejadian yang perlu perhatian segera |
| 🛣️ Jalan Rusak | Kerusakan atau gangguan kondisi jalan |
| 🌧️ Bencana | Banjir, longsor, atau kejadian bencana lainnya |
| 🚔 Kriminalitas | Kejadian yang perlu ditinjau petugas berwenang |
| 🗑️ Sampah | Timbunan sampah atau masalah kebersihan |
| 📌 Lainnya | Kejadian yang tidak masuk kategori di atas |

Kategori bukan pengganti penilaian atau tindakan layanan darurat. Dalam situasi yang mengancam nyawa, hubungi layanan darurat terlebih dahulu.

## 🔄 Alur penggunaan

### Mengirim laporan

1. Buka **Lapor Cepat** dan pilih kategori yang sesuai.
2. Ambil atau unggah foto bukti.
3. Izinkan akses lokasi atau pastikan titik koordinat yang ditampilkan sudah benar.
4. Tambahkan keterangan dan kontak bila diperlukan, lalu kirim laporan.
5. Simpan kode lacak yang ditampilkan setelah pengiriman berhasil.

Laporan dapat dibuat tanpa akun. Jika warga sudah masuk saat mengirim laporan, laporan tersebut dapat ditautkan ke akun dan ditampilkan dalam riwayat dashboard warga.

### Melacak laporan

1. Buka halaman **Lacak**.
2. Masukkan kode `JL-XXXXXX` yang diperoleh saat laporan dikirim.
3. Lihat status laporan yang tersedia.

Kode lacak bersifat privat seperti kredensial akses: jangan bagikan kepada orang yang tidak berwenang. Kode yang hilang tidak dapat digantikan dengan token rahasia karena fitur token memang tidak digunakan.

### Meninjau laporan sebagai petugas

1. Petugas masuk ke halaman `/masuk` menggunakan akun petugas.
2. Buka antrean laporan untuk melakukan pencarian dan penyaringan.
3. Tinjau detail, bukti, koordinat, skor kepercayaan, dan riwayat yang tersedia.
4. Perbarui verifikasi atau status sesuai kewenangan serta alur status aplikasi.
5. Gunakan halaman statistik atau ekspor CSV untuk rekap.

## 🧭 Halaman dan alamat

### Halaman publik

| Halaman | URL | Keterangan |
|---|---|---|
| 🏠 Beranda | `/` | Pengenalan layanan, kategori, cara kerja, cuaca, FAQ, dan tautan utama |
| 📝 Lapor | `/lapor` | Formulir laporan, unggah foto, GPS, dan pratinjau lokasi |
| ✅ Konfirmasi laporan | `/lapor/selesai/{kode}` | Konfirmasi setelah pengiriman berhasil |
| 🔎 Lacak | `/lacak` | Melihat perkembangan laporan menggunakan kode lacak |
| 🗺️ Peta | `/peta` | Peta laporan yang telah diverifikasi valid |
| 📡 Data peta | `/peta/data` | Data yang digunakan untuk menampilkan penanda peta |
| 🚨 Darurat | `/darurat` | Kontak, titik evakuasi, dan peringatan yang tersedia |
| 🔐 Masuk | `/masuk` | Form masuk warga atau petugas |
| 🧾 Daftar warga | `/daftar` | Pendaftaran akun warga |

### Halaman warga

Halaman berikut memerlukan autentikasi sebagai warga.

| Halaman | URL | Keterangan |
|---|---|---|
| 📋 Dashboard warga | `/warga/dashboard` | Ringkasan dan riwayat laporan milik warga |
| 📄 Detail laporan | `/warga/laporan/{id}` | Tampilan baca-saja untuk detail laporan warga |
| 🚪 Keluar | `/keluar` | Mengakhiri sesi akun |

### Halaman admin

Halaman admin memerlukan autentikasi dan pemeriksaan peran di sisi server.

| Halaman | URL | Akses |
|---|---|---|
| 📥 Antrean laporan | `/admin/laporan` | Admin Instansi dan Super Admin |
| 🧾 Detail laporan | `/admin/laporan/{id}` | Admin yang berwenang dan Super Admin |
| 📊 Statistik | `/admin/statistik` | Admin Instansi dan Super Admin |
| 📤 Ekspor CSV | `/admin/statistik/csv` | Admin Instansi dan Super Admin |
| 🚨 Pengelolaan darurat | `/admin/darurat` | Admin Instansi dan Super Admin |
| 👥 Pengguna admin | `/admin/pengguna` | Super Admin |

## 👥 Peran dan hak akses

| Peran | Masuk menggunakan | Hak akses utama |
|---|---|---|
| 🌐 Pengunjung | Tidak perlu akun | Melihat halaman publik, membuat laporan, dan melacak dengan kode laporan |
| 👤 Warga | NIK 16 digit | Mengirim laporan yang terhubung dengan akun, melihat dashboard dan detail laporan sendiri |
| 🏢 Admin Instansi | Email dan kata sandi | Meninjau laporan instansinya, mengelola status/verifikasi, informasi darurat, statistik, dan CSV |
| ⭐ Super Admin | Email dan kata sandi | Mengakses seluruh laporan dan mengelola akun Admin Instansi |

**Catatan keamanan login warga:** NIK adalah data sensitif. Pada implementasi saat ini, login warga hanya meminta NIK dan tidak meminta kata sandi. Perlakukan NIK dengan sangat hati-hati dan pastikan dampak model autentikasi ini telah dipertimbangkan sebelum aplikasi digunakan untuk layanan publik berskala produksi.

## 🧰 Teknologi

| Lapisan | Teknologi |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| Database | Driver yang didukung Laravel; konfigurasi contoh menggunakan SQLite |
| Template | Blade |
| Frontend | Vite, Tailwind CSS 4, Alpine.js 3 |
| Peta | Leaflet dan tile OpenStreetMap |
| Prakiraan cuaca | Open-Meteo Forecast API |
| Pengujian | PHPUnit tersedia; beberapa tes ditulis dengan sintaks Pest |

### Prasyarat

- PHP **8.2 atau lebih baru** dengan ekstensi yang dibutuhkan Laravel dan driver database pilihan.
- Composer.
- Node.js dan npm.
- Database yang sesuai dengan konfigurasi `.env`.
- Hak tulis pada direktori `storage/` dan `bootstrap/cache/`.

## 🚀 Instalasi dan menjalankan aplikasi

Jalankan perintah berikut dari direktori root repository.

### 1. Pasang dependensi

```bash
composer install
npm install
```

### 2. Siapkan file lingkungan

Salin `.env.example` menjadi `.env`, kemudian sesuaikan nama aplikasi, alamat, dan konfigurasi database. Untuk instalasi lokal SQLite, konfigurasi contoh sudah mengarah ke SQLite.

```dotenv
APP_NAME="JagaLampung"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite

ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=ganti-dengan-password-kuat
```

Pastikan database SQLite tersedia pada lokasi yang sesuai konfigurasi. Jika menggunakan database lain, isi juga host, port, nama database, username, dan password.

> 🔒 Jangan membagikan atau mengunggah `.env`, `APP_KEY`, kredensial database, NIK, maupun kata sandi. Jangan memakai `APP_DEBUG=true` atau kredensial contoh di server produksi.

### 3. Buat application key dan jalankan migrasi

```bash
php artisan key:generate
php artisan migrate
```

`APP_KEY` harus konsisten dan dijaga kerahasiaannya. Aplikasi menggunakannya sebagai bagian dari mekanisme enkripsi dan hash data tertentu.

### 4. Isi data awal dan buat akun Super Admin

Isi `ADMIN_EMAIL` dan `ADMIN_PASSWORD` di `.env`, lalu jalankan:

```bash
php artisan db:seed --class=JagaLampungSeeder
```

Seeder membuat instansi awal, nomor layanan darurat `112`, dan akun Super Admin dari variabel lingkungan. Akun Super Admin tidak dibuat jika email atau kata sandi tidak disediakan. Nomor 112 yang di-seed belum memiliki sumber dan tanggal verifikasi; verifikasi informasi tersebut sebelum mengandalkannya.

### 5. Siapkan file publik dan frontend

```bash
php artisan storage:link
npm run build
```

Foto laporan disimpan pada disk `public`. Pastikan direktori penyimpanan dan symlink publik bekerja sesuai konfigurasi deployment.

### 6. Jalankan server lokal

```bash
php artisan serve
```

Buka URL lokal yang dicetak oleh Artisan. Untuk pengembangan frontend dengan hot reload, jalankan pada terminal lain:

```bash
npm run dev
```

Untuk produksi, gunakan web server dan panduan deployment Laravel; `php artisan serve` ditujukan untuk pengembangan lokal, bukan server produksi.

## 🛡️ Privasi, keamanan, dan moderasi

### Data akun dan laporan

| Data | Perlakuan aplikasi |
|---|---|
| NIK warga | Disimpan terenkripsi dan memiliki hash untuk pencarian/pemeriksaan keunikan; jangan dicatat atau dibagikan sembarangan. |
| Kontak laporan | Opsional dan disimpan terenkripsi oleh model laporan. |
| Foto laporan | Disimpan di disk publik Laravel; atur akses, cadangan, dan retensinya dengan hati-hati. |
| IP dan identitas perangkat | Digunakan dalam bentuk hash untuk pembatasan dan sinyal moderasi. |
| Data peta publik | Hanya laporan valid; koordinat diperhalus dan kontak serta foto tidak dimasukkan ke data peta. |
| Ekspor CSV | Tidak menyertakan kontak pelapor dan memiliki perlindungan terhadap formula CSV berbahaya. |

### Sinyal anti-spam

| Perlindungan | Fungsi |
|---|---|
| ⏱️ Pembatasan pengiriman | Membatasi pengiriman laporan per hash IP; batas aktif pada alur kirim adalah 5 laporan per jam. |
| 🕳️ Honeypot dan waktu pengisian | Menandai pola pengiriman otomatis atau pengisian yang terlalu cepat. |
| 🖼️ Validasi foto | Menerima JPG, JPEG, PNG, atau WEBP hingga 5 MB. |
| 📍 Pemeriksaan koordinat | Memvalidasi rentang koordinat umum Lampung; ini bukan batas wilayah resmi. |
| 🧮 Skor kepercayaan | Memberi skor dan alasan untuk membantu petugas meninjau laporan. Skor tidak otomatis menentukan kebenaran. |
| 🔁 Idempotensi | Mencegah pengiriman ulang permintaan yang sama membuat duplikat. |
| 🚑 Prioritas darurat | Kategori kecelakaan dan bencana memperoleh prioritas tinggi. |

Laporan yang mencurigakan tetap perlu ditinjau manusia. Skor rendah bukan bukti bahwa laporan palsu.

## 🧪 Pengujian

### Build frontend

```bash
npm run build
```

### Tes aplikasi

```bash
php artisan test
```

Beberapa file tes menggunakan sintaks Pest (`it()`), sementara manifest Composer saat ini mencantumkan PHPUnit tetapi belum mencantumkan Pest. Jika tes gagal dengan pesan bahwa fungsi `it()` tidak ditemukan, pasang dependensi Pest yang sesuai untuk Laravel 12 atau konversikan tes tersebut ke PHPUnit sebelum menjalankan suite.

Jangan arahkan konfigurasi database pengujian ke database produksi. Setelah perubahan backend, uji juga alur yang terkait seperti laporan, autentikasi, akses antarperan, dan ekspor.

### Pemeriksaan dokumentasi dan perubahan

```bash
git diff --check
```

## 🌦️ Sumber cuaca dan peta

| Integrasi | Sumber dan batasan |
|---|---|
| Prakiraan cuaca | Open-Meteo Forecast API untuk koordinat sekitar Bandar Lampung (`-5.45, 105.26`), dengan zona waktu Asia/Jakarta dan prakiraan tujuh hari. Tidak ada integrasi feed BMKG pada implementasi saat ini. |
| Peta | Leaflet menampilkan tile OpenStreetMap. Untuk penggunaan produksi atau trafik tinggi, patuhi kebijakan penyedia tile dan pertimbangkan penyedia yang sesuai. |
| Peringatan | Peringatan pada halaman darurat merupakan masukan manual admin, bukan feed otomatis BMKG. |
| Kontak darurat | Data awal 112 memerlukan verifikasi dan pencatatan sumber resmi sebelum dipublikasikan sebagai informasi yang terjamin. |
| Titik evakuasi | Data resmi belum disediakan otomatis; admin perlu memasukkan dan memverifikasi data operasional. |

## ⚠️ Batasan yang perlu diketahui

- Belum ada integrasi peringatan cuaca otomatis dari BMKG. Data prakiraan Open-Meteo di beranda bergantung pada layanan internet pihak ketiga.
- Nomor kontak darurat awal, peringatan, dan titik evakuasi perlu diverifikasi oleh pengelola berdasarkan sumber resmi setempat.
- CAPTCHA, pembersihan EXIF foto di server, pHash, dan pengelompokan duplikat otomatis belum menjadi bagian aktif dari alur penerimaan laporan.
- Notifikasi Web Push, pelaporan offline, batas/poligon kecamatan, dan peta choropleth belum tersedia.
- Pemulihan atau lupa kata sandi belum disediakan.
- Login warga saat ini menggunakan NIK tanpa kata sandi. Evaluasi model autentikasi, komunikasi kepada pengguna, dan risiko akses sebelum penggunaan produksi.
- Statistik waktu respons bergantung pada data perubahan status yang tercatat dan mungkin belum bermakna pada instalasi baru.
- Data contoh atau hasil seeder tidak menggantikan pemeriksaan dan verifikasi operasional.

## 📚 Dokumentasi terkait

| Dokumen | Isi |
|---|---|
| [`docs/IMPLEMENTATION_STATUS.md`](docs/IMPLEMENTATION_STATUS.md) | Catatan implementasi, fitur, dan status validasi |
| [`docs/ANTI_SPAM_DESIGN.md`](docs/ANTI_SPAM_DESIGN.md) | Desain dan batasan moderasi anti-spam |
| [`docs/DATA_SOURCES.md`](docs/DATA_SOURCES.md) | Status sumber cuaca, peta, kontak, dan data wilayah |

## 🗂️ Struktur repository

| Direktori / file | Fungsi |
|---|---|
| `app/` | Controller, model, middleware, enum, dan service aplikasi |
| `bootstrap/` | Inisialisasi framework Laravel |
| `config/` | Konfigurasi aplikasi, autentikasi, dan moderasi |
| `database/` | Migrasi, seeder, factory, dan database lokal |
| `docs/` | Dokumentasi status, sumber data, dan desain anti-spam |
| `public/` | Entry point web dan aset yang dipublikasikan |
| `resources/css/` | CSS aplikasi dan tema Tailwind |
| `resources/js/` | JavaScript antarmuka, GPS, peta, dan cuaca |
| `resources/views/` | Template Blade untuk publik, warga, dan admin |
| `routes/` | Definisi rute web dan console |
| `storage/` | Log, cache, dan berkas aplikasi |
| `tests/` | Tes unit dan fitur |
| `composer.json` | Dependensi dan perintah PHP |
| `package.json` | Dependensi serta skrip frontend |
| `.env.example` | Contoh konfigurasi lingkungan tanpa kredensial aplikasi |

## 🤝 Pengembangan

Sebelum mengirim perubahan:

1. Jalankan build dan tes yang relevan.
2. Tinjau rute dan otorisasi server untuk setiap fitur yang menyentuh data laporan atau akun.
3. Pastikan informasi privat tidak masuk ke peta publik, log, atau ekspor.
4. Uji antarmuka pada ukuran layar ponsel dan desktop bila tampilan berubah.
5. Perbarui README atau dokumentasi terkait jika alur penggunaan, konfigurasi, atau batasan aplikasi berubah.

---

**JagaLampung — lihat masalah, laporkan dengan bukti, pantau sampai ditangani.** 🌿
