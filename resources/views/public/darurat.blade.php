@extends('layouts.app')
@section('title', 'Informasi Darurat')
@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:py-12">
  <div class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-red-700 via-red-600 to-rose-500 px-6 py-8 text-white shadow-xl shadow-red-900/15 sm:px-10 sm:py-10">
    <div class="absolute -right-10 -top-16 -z-10 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
    <p class="text-sm font-bold uppercase tracking-[.16em] text-red-100">JagaLampung • Siaga</p>
    <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Informasi Darurat</h1>
    <p class="mt-3 max-w-xl text-sm leading-relaxed text-red-50 sm:text-base">Jika menghadapi situasi yang mengancam jiwa, segera hubungi layanan darurat.</p>
    <a href="tel:112" class="btn mt-6 flex w-full justify-center whitespace-nowrap rounded-xl bg-white px-5 text-base font-extrabold text-red-700 shadow-lg shadow-red-950/15 hover:bg-red-50 sm:px-7 sm:text-lg md:w-auto md:min-w-48">
      <span aria-hidden="true">☎</span> Telepon 112
    </a>
  </div>

  <section class="mt-8 sm:mt-10">
    <div class="mb-4 flex items-center gap-3">
      <span class="grid h-10 w-10 place-items-center rounded-xl bg-amber-100 text-lg text-amber-700" aria-hidden="true">!</span>
      <div><h2 class="text-xl font-extrabold sm:text-2xl">Peringatan</h2><p class="text-sm text-slate-500 dark:text-slate-400">Informasi kondisi darurat terkini</p></div>
    </div>
  @forelse ($alerts as $a)
    <div class="reveal is-in mt-3 rounded-2xl border p-4 shadow-sm sm:p-5 {{ ['bahaya'=>'border-red-200 bg-red-50','waspada'=>'border-amber-200 bg-amber-50'][$a->severity] ?? 'border-blue-200 bg-blue-50' }} text-slate-900">
      <p class="text-xs font-extrabold uppercase tracking-wider {{ $a->severity === 'bahaya' ? 'text-red-700' : ($a->severity === 'waspada' ? 'text-amber-700' : 'text-blue-700') }}">{{ strtoupper($a->severity) }}</p>
      <p class="mt-1 text-lg font-bold">{{ $a->title }}</p><p class="mt-1 leading-relaxed">{{ $a->body }}</p>
      <p class="mt-3 text-xs text-slate-600">Asal: {{ $a->origin === 'feed' ? 'feed resmi' : 'input manual admin' }} • terbit {{ $a->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p></div>
  @empty
    <div class="flex gap-4 rounded-2xl border border-jl-100 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
      <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-jl-100 text-jl-700" aria-hidden="true">✓</span>
      <div><p class="font-bold">Tidak ada peringatan aktif</p><p class="mt-1 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Tidak ada peringatan aktif di JagaLampung. Untuk cuaca resmi, cek BMKG.</p></div>
    </div>
  @endforelse
  </section>

  <section class="mt-8 sm:mt-10">
    <div class="mb-4 flex items-center gap-3">
      <span class="grid h-10 w-10 place-items-center rounded-xl bg-jl-100 text-jl-700" aria-hidden="true">☎</span>
      <div><h2 class="text-xl font-extrabold sm:text-2xl">Nomor penting</h2><p class="text-sm text-slate-500 dark:text-slate-400">Hubungi layanan yang Anda perlukan</p></div>
    </div>
  <div class="grid gap-4 sm:grid-cols-2">
    @foreach ($contacts as $c)
      <div class="spot hover-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
        <div class="flex items-start justify-between gap-3">
          <p class="font-bold leading-snug sm:text-lg">{{ $c->name }}</p>
        </div>
        <a href="tel:{{ preg_replace('/\s+/', '', $c->number) }}" class="btn btn-primary mt-4 w-full rounded-xl">{{ $c->number }} <span aria-hidden="true">→</span></a>
        @if ($c->verified_at)
          <p class="mt-3 text-xs leading-relaxed text-slate-500">Diverifikasi {{ \Illuminate\Support\Carbon::parse($c->verified_at)->format('d/m/Y') }}{{ $c->source_url ? ' • '.$c->source_url : '' }}</p>
        @endif
      </div>
    @endforeach
  </div>
  </section>

  <section class="mt-8 sm:mt-10">
    <div class="mb-4 flex items-center gap-3">
      <span class="grid h-10 w-10 place-items-center rounded-xl bg-sky-100 text-sky-700" aria-hidden="true">⌖</span>
      <div><h2 class="text-xl font-extrabold sm:text-2xl">Titik evakuasi</h2><p class="text-sm text-slate-500 dark:text-slate-400">Lokasi untuk menuju tempat aman</p></div>
    </div>
  @forelse ($points as $p)
    <div class="mt-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
      <p class="font-bold sm:text-lg">{{ $p->name }}</p>
      <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $p->address }} @if ($p->capacity) • kapasitas {{ $p->capacity }}@endif</p>
      <a class="mt-4 inline-flex min-h-11 items-center gap-2 rounded-xl bg-jl-50 px-4 text-sm font-bold text-jl-700 transition hover:bg-jl-100 dark:bg-white/10 dark:text-jl-500 dark:hover:bg-white/15" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $p->lat }}&mlon={{ $p->lng }}#map=17/{{ $p->lat }}/{{ $p->lng }}">Lihat di peta <span aria-hidden="true">↗</span></a>
    </div>
  @empty
    <div class="rounded-2xl border border-slate-200 bg-white p-5 text-sm leading-relaxed text-slate-600 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-slate-300">Data titik evakuasi resmi belum tersedia. Admin berwenang dapat menambahkannya dari sumber BPBD/pemda.</div>
  @endforelse
  </section>
</div>
@endsection
