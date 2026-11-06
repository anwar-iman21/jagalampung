@extends('layouts.app')
@section('content')
{{-- HERO --}}
<section class="hero-banner relative isolate overflow-hidden bg-gradient-to-br from-jl-900 via-jl-700 to-jl-600 text-white">
  <div class="hero-glow hero-glow-one" aria-hidden="true"></div>
  <div class="hero-glow hero-glow-two" aria-hidden="true"></div>
  <div class="hero-orb hero-orb-one" aria-hidden="true"></div>
  <div class="hero-orb hero-orb-two" aria-hidden="true"></div>
  <div class="hero-orb hero-orb-three" aria-hidden="true"></div>
  <div class="hero-grid absolute inset-0 z-0 opacity-20" aria-hidden="true"></div>
  <div class="relative z-10 mx-auto flex min-h-[30rem] max-w-5xl items-center justify-center px-5 py-20 text-center sm:min-h-[34rem] sm:px-8 sm:py-24">
    <div class="w-full">
      <p class="reveal mx-auto inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[.18em] text-green-100 shadow-lg shadow-jl-900/10 backdrop-blur sm:text-sm" style="--i:0">
        <span class="hero-status-dot" aria-hidden="true"></span> Bersama jaga Lampung
      </p>
      <h1 class="hero-heading reveal mx-auto mt-7 max-w-4xl font-display text-[clamp(2.65rem,7.2vw,5rem)] font-semibold leading-[1.08] tracking-[-.045em]" style="--i:1">
        <span class="block">Lihat masalah?</span>
        <span class="hero-title-accent mt-1 block">Foto, kirim, selesai.</span>
      </h1>
      <p class="reveal mx-auto mt-6 max-w-2xl text-base leading-relaxed text-green-100 sm:mt-7 sm:text-lg" style="--i:2">Laporkan jalan rusak, bencana, kecelakaan, dan sampah dalam hitungan detik. Pantau penanganannya sampai tuntas.</p>
      <div class="reveal mt-8 flex flex-col items-stretch justify-center gap-3 sm:mt-9 sm:flex-row sm:items-center" style="--i:3">
        <a href="{{ route('lapor') }}" class="btn hero-cta-primary pulse-soft w-full rounded-2xl px-7 text-base sm:w-auto sm:text-lg">Lapor Sekarang <span aria-hidden="true">→</span></a>
        <a href="{{ route('lacak') }}" class="btn w-full rounded-2xl border border-white/35 bg-white/5 px-7 text-white backdrop-blur transition hover:border-white/60 hover:bg-white/10 sm:w-auto">Lacak Laporan</a>
      </div>
      <p class="reveal mt-5 text-xs font-medium tracking-wide text-green-100/75 sm:text-sm" style="--i:4">Mudah dilaporkan <span class="mx-1 text-green-300">•</span> Transparan dipantau <span class="mx-1 text-green-300">•</span> Untuk Lampung</p>
    </div>
  </div>
</section>

{{-- CARA KERJA --}}
<section id="cara" class="mx-auto max-w-7xl px-4 pt-24">
  <h2 class="reveal text-center text-3xl font-extrabold">Cara kerja</h2>
  <div class="relative mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ([['📷','Foto','Ambil foto kejadian, lokasi terdeteksi otomatis.'],['🚀','Kirim','Pilih kategori lalu kirim. Dapat kode lacak.'],['🔎','Diverifikasi','Admin memeriksa keaslian laporan.'],['🛠️','Ditindak','Instansi menangani, status terus diperbarui.']] as $i => [$ic,$t,$d])
      <div class="reveal spot hover-lift rounded-3xl bg-white p-6 shadow dark:bg-white/5" style="--i:{{ $i }}">
        <div class="mb-3 grid h-14 w-14 place-items-center rounded-2xl bg-jl-100 text-2xl">{{ $ic }}</div>
        <p class="text-sm font-bold text-jl-600">Langkah {{ $i + 1 }}</p><h3 class="text-xl font-bold">{{ $t }}</h3><p class="mt-1 text-slate-600 dark:text-slate-300">{{ $d }}</p>
      </div>
    @endforeach
  </div>
</section>

{{-- KATEGORI --}}
<section id="kategori" class="mx-auto max-w-7xl px-4 pt-24">
  <h2 class="reveal text-center text-3xl font-extrabold">Apa yang ingin Anda laporkan?</h2>
  <div class="mt-10 grid grid-cols-2 gap-4 lg:grid-cols-5">
    @foreach ([['kecelakaan','🚨','Kecelakaan / Darurat'],['jalan_rusak','🕳️','Jalan Rusak'],['bencana','🌊','Bencana'],['kriminalitas','🛡️','Kriminalitas'],['sampah','🗑️','Sampah']] as $i => [$k,$ic,$t])
      <a href="{{ route('lapor', ['kategori' => $k]) }}" class="reveal spot hover-lift block rounded-3xl bg-white p-6 text-center shadow dark:bg-white/5" style="--i:{{ $i }}">
        <div class="text-4xl transition group-hover:scale-110">{{ $ic }}</div><p class="mt-3 font-bold">{{ $t }}</p></a>
    @endforeach
  </div>
</section>

{{-- ANGKA (nyata dari DB) --}}
<section class="mx-auto max-w-7xl px-4 pt-24">
  <div class="grid gap-4 rounded-3xl bg-jl-900 p-8 text-center text-white sm:grid-cols-3">
    @foreach ([['Laporan terverifikasi',$stats['total']],['Selesai ditangani',$stats['selesai']]] as [$l,$v])
      <div class="reveal" x-data="counter({{ $v }})" @revealed.window="run()" x-intersect.once="run()"><p class="text-5xl font-extrabold" x-text="n">0</p><p class="mt-1 text-green-200">{{ $l }}</p></div>
    @endforeach
    <div class="reveal"><p class="text-5xl font-extrabold">—</p><p class="mt-1 text-green-200">Rata-rata waktu respons (belum ada data)</p></div>
  </div>
</section>

{{-- TRANSPARANSI --}}
<section class="mx-auto max-w-5xl px-4 pt-24 text-center">
  <h2 class="reveal text-3xl font-extrabold">Dipercaya karena transparan</h2>
  <p class="reveal mx-auto mt-4 max-w-2xl text-slate-600 dark:text-slate-300" style="--i:1">Setiap laporan masuk antrean admin. Hanya laporan yang terverifikasi tampil di peta publik. Laporan darurat selalu diprioritaskan, dan kami menyaring spam agar laporan asli tidak tenggelam.</p>
</section>

{{-- DARURAT --}}
<section id="darurat" class="mx-auto max-w-5xl px-4 pt-24">
  <div class="reveal rounded-3xl border border-red-200 bg-red-50 p-8 text-center dark:bg-red-950/30">
    <h2 class="text-2xl font-extrabold text-red-700">Situasi mengancam jiwa?</h2>
    <p class="mt-2">Hubungi layanan darurat nasional terlebih dahulu.</p>
    <a href="tel:112" class="btn mt-5 bg-red-600 text-xl text-white hover:bg-red-700">📞 Telepon 112</a>
  </div>
</section>

{{-- FAQ --}}
<section id="faq" class="mx-auto max-w-3xl px-4 pt-16 sm:pt-24">
  <h2 class="reveal text-center text-2xl font-extrabold sm:text-3xl">Pertanyaan umum</h2>
  <div class="mt-6 space-y-3 sm:mt-8" x-data="{ o: null }">
    @foreach ([['Perlu login untuk melapor?','Tidak. Anda cukup foto dan kirim. Simpan kode lacak dan token Anda.'],['Kenapa laporan saya belum tampil di peta?','Laporan tampil publik setelah diverifikasi admin.'],['Apakah kontak saya dipublikasikan?','Tidak. Kontak bersifat opsional, disimpan terenkripsi, dan hanya terlihat admin berwenang.']] as $i => [$q,$a])
      <div class="reveal overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5 dark:bg-white/5 dark:ring-white/10">
        <button id="faq-question-{{ $i }}" aria-controls="faq-answer-{{ $i }}" class="flex min-h-14 w-full items-center justify-between gap-4 px-4 py-4 text-left font-bold leading-relaxed sm:min-h-16 sm:px-6" @click="o = o === {{ $i }} ? null : {{ $i }}" :aria-expanded="o === {{ $i }}">
          <span>{{ $q }}</span>
          <span aria-hidden="true" class="shrink-0 text-xl font-medium transition-transform duration-200" :class="o === {{ $i }} && 'rotate-45'">＋</span>
        </button>
        <div id="faq-answer-{{ $i }}" x-show="o === {{ $i }}" x-collapse x-cloak role="region" aria-labelledby="faq-question-{{ $i }}" class="break-words px-4 pb-4 text-sm leading-relaxed text-slate-600 sm:px-6 sm:pb-6 sm:text-base dark:text-slate-300">{{ $a }}</div>
      </div>
    @endforeach
  </div>
</section>

<section class="mx-auto mt-16 max-w-5xl px-4 sm:mt-24">
  <div class="reveal rounded-3xl bg-gradient-to-r from-jl-700 to-jl-500 p-6 text-center text-white sm:p-10">
    <h2 class="text-2xl font-extrabold sm:text-3xl">Bantu jaga Lampung hari ini</h2>
    <a href="{{ route('lapor') }}" class="btn mt-6 bg-white text-jl-700">Lapor Cepat</a>
  </div>
</section>
@endsection
