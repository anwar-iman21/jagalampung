import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import collapse from '@alpinejs/collapse';
import '../css/app.css';

window.Alpine = Alpine;
Alpine.plugin(intersect);
Alpine.plugin(collapse);

// Tema: hormati preferensi sistem, bisa diganti pengguna
const saved = localStorage.getItem('jl-theme');
if (saved ? saved === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches) document.documentElement.classList.add('dark');

Alpine.data('theme', () => ({
  toggle() { const d = document.documentElement.classList.toggle('dark'); localStorage.setItem('jl-theme', d ? 'dark' : 'light'); },
}));

Alpine.data('nav', () => ({ open: false, scrolled: false, progress: 0, top: false,
  init() { const f = () => { const y = scrollY, h = document.documentElement.scrollHeight - innerHeight;
    this.scrolled = y > 20; this.top = y > 600; this.progress = h > 0 ? (y / h) * 100 : 0; }; f(); addEventListener('scroll', f, { passive: true }); },
}));

// Count-up angka
Alpine.data('counter', (to = 0) => ({ n: 0,
  run() { const t0 = performance.now(), d = 1200; const step = (t) => { const p = Math.min(1, (t - t0) / d);
    this.n = Math.round(to * (1 - Math.pow(1 - p, 3))); if (p < 1) requestAnimationFrame(step); }; requestAnimationFrame(step); },
}));

// Lapor Cepat: live GPS and in-browser camera with client-side image compression.
Alpine.data('lapor', () => ({
  lat: '', lng: '', acc: '', geo: 'Meminta akses GPS berakurasi tinggi…',
  geoUpdatedAt: '', manualLocation: false, geoWatchId: null,
  preview: null, photoBusy: false, photoError: '', cameraOpen: false, cameraBusy: false, cameraError: '',
  cameraStream: null, busy: false,
  idem: crypto.randomUUID ? crypto.randomUUID() : String(Date.now()),
  init() { this.startLocation(); },
  destroy() {
    if (this.geoWatchId !== null) navigator.geolocation.clearWatch(this.geoWatchId);
    this.stopCamera();
    if (this.preview) URL.revokeObjectURL(this.preview);
  },
  startLocation() {
    this.manualLocation = false;
    this.lat = '';
    this.lng = '';
    this.acc = '';
    this.geoUpdatedAt = '';
    if (!navigator.geolocation) {
      this.geo = 'GPS tidak didukung browser ini. Isi koordinat secara manual.';
      return;
    }
    if (this.geoWatchId !== null) navigator.geolocation.clearWatch(this.geoWatchId);
    this.geo = 'Mencari sinyal GPS berakurasi tinggi…';
    try {
      this.geoWatchId = navigator.geolocation.watchPosition((position) => {
        if (this.manualLocation) return;
        const accuracy = Math.round(position.coords.accuracy);
        this.lat = position.coords.latitude.toFixed(7);
        this.lng = position.coords.longitude.toFixed(7);
        this.acc = accuracy;
        this.geoUpdatedAt = new Date(position.timestamp).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const quality = accuracy <= 20 ? 'Lokasi akurat' : accuracy <= 50 ? 'Lokasi cukup akurat' : 'Akurasi GPS masih rendah, tunggu sinyal lebih baik';
        this.geo = `${quality} (±${accuracy} m) • diperbarui ${this.geoUpdatedAt}`;
      }, (error) => {
        const messages = {
          1: 'Izin lokasi ditolak. Aktifkan izin lokasi atau isi koordinat secara manual.',
          2: 'Sinyal GPS belum tersedia. Coba lagi di area terbuka atau isi koordinat manual.',
          3: 'GPS terlalu lama merespons. Coba lagi atau isi koordinat manual.',
        };
        this.geo = messages[error.code] || 'GPS gagal diakses. Coba lagi atau isi koordinat manual.';
      }, { enableHighAccuracy: true, maximumAge: 0, timeout: 20000 });
    } catch (error) {
      this.geo = 'GPS tidak dapat diakses browser ini. Isi koordinat secara manual.';
      console.error('Gagal memulai GPS:', error);
    }
  },
  setManualLocation() {
    this.manualLocation = true;
    this.geoUpdatedAt = '';
    this.acc = '';
    this.geo = 'Menggunakan koordinat yang Anda isi secara manual.';
  },
  async openCamera() {
    this.cameraError = '';
    this.cameraOpen = true;
    if (!window.isSecureContext) {
      this.cameraOpen = false;
      this.cameraError = 'Akses kamera memerlukan HTTPS atau localhost.';
      return;
    }
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      this.cameraOpen = false;
      this.cameraError = 'Browser ini tidak mendukung kamera langsung. Gunakan tombol pilih foto.';
      return;
    }
    this.cameraBusy = true;
    try {
      this.cameraStream = await navigator.mediaDevices.getUserMedia({
        audio: false,
        video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } },
      });
      await this.$nextTick();
      const video = this.$refs.cameraVideo;
      video.srcObject = this.cameraStream;
      await video.play();
      this.cameraBusy = false;
    } catch (error) {
      this.stopCamera();
      const messages = {
        NotAllowedError: 'Izin kamera ditolak. Izinkan kamera di browser lalu coba lagi.',
        NotFoundError: 'Kamera tidak ditemukan pada perangkat ini.',
        NotReadableError: 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.',
        OverconstrainedError: 'Kamera tidak mendukung pengaturan yang diminta. Coba pilih foto dari perangkat.',
      };
      this.cameraError = messages[error.name] || 'Kamera gagal dibuka. Periksa izin kamera atau pilih foto dari perangkat.';
      console.error('Gagal membuka kamera:', error);
    }
  },
  stopCamera() {
    if (this.cameraStream) this.cameraStream.getTracks().forEach((track) => track.stop());
    this.cameraStream = null;
    this.cameraBusy = false;
    this.cameraOpen = false;
  },
  async capturePhoto() {
    const video = this.$refs.cameraVideo;
    if (this.cameraBusy) return;
    if (!video || !video.videoWidth || !video.videoHeight) {
      this.cameraError = 'Kamera belum siap. Tunggu sebentar lalu ambil foto lagi.';
      return;
    }
    try {
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const context = canvas.getContext('2d');
      if (!context) throw new Error('Canvas tidak dapat dibuat.');
      context.drawImage(video, 0, 0);
      const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', 0.92));
      if (!blob) throw new Error('Foto gagal diambil.');
      this.stopCamera();
      await this.attachPhoto(new File([blob], 'laporan.jpg', { type: 'image/jpeg' }));
    } catch (error) {
      this.cameraError = 'Foto gagal diambil. Coba lagi atau pilih foto dari perangkat.';
      console.error('Gagal mengambil foto dari kamera:', error);
    }
    if (this.cameraError) {
      this.stopCamera();
    }
  },
  async pick(event) {
    const file = event.target.files[0];
    if (file) await this.attachPhoto(file);
  },
  async attachPhoto(file) {
    this.photoBusy = true;
    this.photoError = '';
    try {
      const image = await createImageBitmap(file);
      const scale = Math.min(1, 1600 / Math.max(image.width, image.height));
      const canvas = Object.assign(document.createElement('canvas'), {
        width: Math.max(1, Math.round(image.width * scale)),
        height: Math.max(1, Math.round(image.height * scale)),
      });
      const context = canvas.getContext('2d');
      if (!context) throw new Error('Canvas tidak dapat dibuat.');
      context.drawImage(image, 0, 0, canvas.width, canvas.height);
      image.close();
      const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', 0.84));
      if (!blob) throw new Error('Foto gagal dikompres.');
      const compressed = new File([blob], 'laporan.jpg', { type: 'image/jpeg' });
      const transfer = new DataTransfer();
      transfer.items.add(compressed);
      this.$refs.photoInput.files = transfer.files;
      if (this.preview) URL.revokeObjectURL(this.preview);
      this.preview = URL.createObjectURL(compressed);
    } catch (error) {
      this.photoError = 'Foto gagal diproses. Coba pilih atau ambil foto lain.';
      console.error('Gagal memproses foto laporan:', error);
    } finally {
      this.photoBusy = false;
    }
  },
  submit(event) {
    if (this.photoBusy || this.photoError) {
      event.preventDefault();
      this.photoError = this.photoBusy ? 'Tunggu sampai foto selesai diproses.' : this.photoError;
      return;
    }
    if (!this.$refs.photoInput.files.length) {
      event.preventDefault();
      this.photoError = 'Foto kejadian wajib diambil atau dipilih sebelum laporan dikirim.';
      return;
    }
    if (!this.lat || !this.lng) {
      event.preventDefault();
      this.geo = 'Lokasi belum tersedia. Tunggu GPS atau isi koordinat secara manual.';
      return;
    }
    this.busy = true;
  },
}));

Alpine.start();

// Reveal saat scroll (sekali), spotlight kartu, parallax ringan
const io = new IntersectionObserver((es) => es.forEach((x) => { if (x.isIntersecting) { x.target.classList.add('is-in'); io.unobserve(x.target); x.target.dispatchEvent(new Event('revealed')); } }), { threshold: .15 });
document.querySelectorAll('.reveal').forEach((el) => io.observe(el));
document.querySelectorAll('.spot').forEach((el) => el.addEventListener('pointermove', (e) => { const r = el.getBoundingClientRect(); el.style.setProperty('--mx', e.clientX - r.left + 'px'); el.style.setProperty('--my', e.clientY - r.top + 'px'); }));
if (!matchMedia('(prefers-reduced-motion: reduce)').matches && matchMedia('(hover: hover)').matches) {
  const hero = document.querySelector('[data-parallax]');
  hero && addEventListener('pointermove', (e) => hero.querySelectorAll('[data-depth]').forEach((n) => { const d = +n.dataset.depth; n.style.transform = `translate3d(${(e.clientX / innerWidth - .5) * d}px,${(e.clientY / innerHeight - .5) * d}px,0)`; }), { passive: true });
}
