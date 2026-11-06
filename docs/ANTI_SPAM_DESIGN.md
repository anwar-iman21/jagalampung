# Desain Anti-Spam
Prinsip: jangan menolak laporan asli. Laporan mencurigakan tetap diterima, diberi skor, dan diputuskan admin.

Lapisan aktif: rate limit per hash IP (`config/spam.php`), honeypot `website`, batas waktu isi minimum, validasi gambar (`image`, mimes, 5 MB),
validasi koordinat (bounding box kasar Lampung), skor kepercayaan (`ReportTrustScorer`) dengan alasan tersimpan, riwayat perangkat
(spam/valid), blocklist (perangkat, 7 hari dari aksi admin), idempotensi kirim ganda, hash IP/perangkat bersalt (`APP_KEY`).
Prioritas: kategori kecelakaan & bencana selalu prioritas tinggi walau skor rendah.

Skor: basis 50 + bobot sinyal, dibatasi 0–100. Label: ≥70 tinggi, ≥40 perlu dicek, selain itu berisiko spam.
Keterbatasan (belum ada): CAPTCHA, pHash foto, klaster duplikat otomatis di alur kirim (service `DuplicateDetector` sudah ada & teruji),
hapus EXIF di server, poligon kecamatan.
