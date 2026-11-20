@extends('layouts.app')
@section('title', $report->tracking_code)
@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:py-10">
  <a href="{{ route('warga.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-jl-700 hover:underline">← Kembali ke dashboard</a>

  <div class="mt-5 rounded-3xl bg-gradient-to-br from-jl-900 via-jl-700 to-jl-600 p-6 text-white shadow-lg sm:p-8">
    <p class="text-sm font-bold uppercase tracking-[.14em] text-green-200">Detail laporan</p>
    <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
      <div>
        <h1 class="break-all text-3xl font-extrabold sm:text-4xl">{{ $report->tracking_code }}</h1>
        <p class="mt-2 text-sm text-green-50">{{ ucfirst(str_replace('_', ' ', $report->category)) }} <span class="mx-1 text-green-200">•</span> {{ $report->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
      </div>
      <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-sm font-bold">{{ $report->status?->label() ?? 'Tidak diketahui' }}</span>
    </div>
  </div>

  <div class="mt-6 grid gap-5 lg:grid-cols-[minmax(0,1.15fr)_minmax(18rem,.85fr)]">
    <div class="space-y-5">
      <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10 sm:px-6">
          <h2 class="font-extrabold sm:text-lg">Bukti kejadian</h2>
        </div>
        @if ($report->photo_path)
          <a href="{{ asset('storage/'.$report->photo_path) }}" target="_blank" rel="noopener" class="block bg-slate-100 dark:bg-black/20">
            <img src="{{ asset('storage/'.$report->photo_path) }}" alt="Foto laporan {{ $report->tracking_code }}" class="max-h-[34rem] w-full object-contain">
          </a>
        @else
          <div class="grid min-h-48 place-items-center bg-slate-50 text-sm text-slate-500 dark:bg-black/10">Foto tidak tersedia.</div>
        @endif
        <div class="p-5 sm:p-6">
          <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Keterangan</p>
          <p class="mt-2 whitespace-pre-line leading-relaxed">{{ $report->description ?: 'Tidak ada keterangan tambahan.' }}</p>
        </div>
      </section>

      <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
        <h2 class="font-extrabold sm:text-lg">Informasi lokasi</h2>
        <dl class="mt-4 grid gap-3 sm:grid-cols-2">
          <div class="rounded-2xl bg-slate-50 p-4 dark:bg-white/5">
            <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Koordinat</dt>
            <dd class="mt-2 break-all font-semibold">{{ $report->lat }}, {{ $report->lng }}</dd>
          </div>
          <div class="rounded-2xl bg-slate-50 p-4 dark:bg-white/5">
            <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Akurasi</dt>
            <dd class="mt-2 font-semibold">±{{ $report->accuracy ?? '?' }} meter</dd>
          </div>
        </dl>
        <a class="mt-4 inline-flex min-h-10 items-center rounded-lg bg-jl-100 px-3 text-sm font-bold text-jl-700 hover:bg-jl-200" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $report->lat }}&mlon={{ $report->lng }}#map=17/{{ $report->lat }}/{{ $report->lng }}">Buka lokasi di peta ↗</a>
      </section>

      <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
        <h2 class="font-extrabold sm:text-lg">Kontak pelapor</h2>
        <p class="mt-3 break-words text-slate-700 dark:text-slate-300">{{ $report->contact ?: 'Tidak disertakan' }}</p>
      </section>
    </div>

    <div class="space-y-5">
      <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
        <p class="text-xs font-bold uppercase tracking-wide text-jl-700">Status penanganan</p>
        <p class="mt-2 text-xl font-extrabold">{{ $report->status?->label() ?? 'Tidak diketahui' }}</p>
        @if ($report->verification_note)
          <div class="mt-4 rounded-2xl bg-slate-50 p-4 dark:bg-white/5">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Catatan verifikasi</p>
            <p class="mt-2 text-sm leading-relaxed">{{ $report->verification_note }}</p>
          </div>
        @endif
        @if ($report->public_response)
          <div class="mt-3 rounded-2xl bg-jl-50 p-4 text-jl-800 dark:bg-white/5 dark:text-jl-300">
            <p class="text-xs font-bold uppercase tracking-wide">Tanggapan petugas</p>
            <p class="mt-2 text-sm leading-relaxed">{{ $report->public_response }}</p>
          </div>
        @endif
      </section>

      <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
        <h2 class="font-extrabold">Riwayat status</h2>
        <ol class="mt-5 space-y-4">
          @forelse ($history as $item)
            <li class="relative flex gap-3">
              <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full bg-jl-500" aria-hidden="true"></span>
              <div>
                <p class="text-sm font-bold">{{ ucfirst(str_replace('_', ' ', $item->from_status)) }} <span class="text-jl-600">→</span> {{ ucfirst(str_replace('_', ' ', $item->to_status)) }}</p>
                @if ($item->note)<p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $item->note }}</p>@endif
                <p class="mt-1 text-xs text-slate-500">{{ \Illuminate\Support\Carbon::parse($item->created_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
              </div>
            </li>
          @empty
            <li class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500 dark:bg-white/5">Belum ada perubahan status.</li>
          @endforelse
        </ol>
      </section>
    </div>
  </div>
</div>
@endsection
