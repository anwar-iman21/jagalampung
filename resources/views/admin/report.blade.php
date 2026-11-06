@extends('layouts.app')
@section('title', $report->tracking_code)
@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:py-10">
  @include('admin._nav')

  <div class="mb-6 rounded-3xl bg-gradient-to-br from-jl-900 via-jl-700 to-jl-600 p-6 text-white shadow-lg shadow-jl-900/10 sm:p-8">
    <a href="{{ route('admin.reports') }}" class="inline-flex items-center gap-2 text-sm font-bold text-green-100 transition hover:text-white">← Kembali ke antrean</a>
    <div class="mt-5 flex flex-wrap items-end justify-between gap-4">
      <div>
        <p class="text-sm font-bold uppercase tracking-[.14em] text-green-200">Detail laporan</p>
        <h1 class="mt-1 break-all text-3xl font-extrabold sm:text-4xl">{{ $report->tracking_code }}</h1>
        <p class="mt-2 text-sm text-green-50">{{ ucfirst(str_replace('_', ' ', $report->category)) }} <span class="mx-1 text-green-200">•</span> {{ $report->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
      </div>
      <div class="flex flex-wrap gap-2">
        <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-sm font-bold">{{ $report->status->label() }}</span>
        <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-sm font-bold">Verifikasi: {{ ucfirst(str_replace('_', ' ', $report->verification)) }}</span>
      </div>
    </div>
  </div>

  @if (session('ok'))<p class="mb-5 rounded-2xl border border-jl-100 bg-jl-50 p-4 font-medium text-jl-700" role="status">{{ session('ok') }}</p>@endif
  @if ($errors->any())
    <div class="shake mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700" role="alert">
      <p class="font-bold">Perubahan belum dapat disimpan</p>
      @foreach ($errors->all() as $e)<p class="mt-1 text-sm">{{ $e }}</p>@endforeach
    </div>
  @endif

  <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(20rem,.9fr)]">
    <div class="space-y-5">
      <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10 sm:px-6">
          <h2 class="font-extrabold sm:text-lg">Bukti kejadian</h2>
          <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Foto dan keterangan yang dikirim warga.</p>
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
        <h2 class="font-extrabold sm:text-lg">Informasi lokasi & kontak</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
          <div class="rounded-2xl bg-slate-50 p-4 dark:bg-white/5">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Koordinat</p>
            <p class="mt-2 break-all font-semibold">{{ $report->lat }}, {{ $report->lng }}</p>
            <p class="mt-1 text-xs text-slate-500">Akurasi ±{{ $report->accuracy ?? '?' }} m</p>
            <a class="mt-3 inline-flex min-h-10 items-center rounded-lg bg-jl-100 px-3 text-sm font-bold text-jl-700 transition hover:bg-jl-200 dark:bg-white/10 dark:text-jl-500 dark:hover:bg-white/15" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $report->lat }}&mlon={{ $report->lng }}#map=17/{{ $report->lat }}/{{ $report->lng }}">Buka peta ↗</a>
          </div>
          <div class="rounded-2xl bg-slate-50 p-4 dark:bg-white/5">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Kontak pelapor</p>
            <p class="mt-2 break-words font-semibold">{{ $report->contact ?: 'Tidak disertakan' }}</p>
            <p class="mt-1 text-xs text-slate-500">Informasi ini hanya untuk admin berwenang.</p>
          </div>
        </div>
      </section>

      <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div><h2 class="font-extrabold sm:text-lg">Skor kepercayaan</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Indikator untuk membantu proses peninjauan.</p></div>
          <div class="text-right"><p class="text-2xl font-extrabold text-jl-700 dark:text-jl-500">{{ $report->trust_score }}<span class="text-sm font-semibold text-slate-400"> / 100</span></p><p class="text-xs font-bold">{{ ucfirst(str_replace('_', ' ', $report->trust_label)) }}</p></div>
        </div>
        <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-100 dark:bg-white/10" role="progressbar" aria-label="Skor kepercayaan" aria-valuenow="{{ max(0, min(100, (int) $report->trust_score)) }}" aria-valuemin="0" aria-valuemax="100">
          <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-jl-500" style="width: {{ max(0, min(100, (int) $report->trust_score)) }}%"></div>
        </div>
        @if ($report->trust_reasons)
          <ul class="mt-4 grid gap-2 sm:grid-cols-2">
            @foreach (($report->trust_reasons ?? []) as $k => $v)
              <li class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-3 py-2 text-sm dark:bg-white/5"><span>{{ ucfirst(str_replace('_', ' ', $k)) }}</span><span class="font-bold {{ $v > 0 ? 'text-jl-700 dark:text-jl-500' : ($v < 0 ? 'text-red-600' : 'text-slate-500') }}">{{ $v > 0 ? '+' : '' }}{{ $v }}</span></li>
            @endforeach
          </ul>
        @endif
      </section>
    </div>

    <div class="space-y-5 lg:sticky lg:top-20">
      <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
        <div class="mb-5">
          <p class="text-xs font-bold uppercase tracking-wide text-jl-700 dark:text-jl-500">Tindak lanjut</p>
          <h2 class="mt-1 text-xl font-extrabold">Perbarui laporan</h2>
          <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih tindakan dan simpan perubahan status.</p>
        </div>
        <form method="POST" action="{{ route('admin.report.update', $report) }}" class="space-y-4">@csrf
          <label class="block text-sm font-bold" for="report-action">Tindakan
            <select id="report-action" name="action" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-[#0b1410]">
              <option value="valid">Valid (publikasikan)</option><option value="diproses">Ubah ke Diproses</option>
              <option value="selesai">Ubah ke Selesai</option><option value="tidak_valid">Tidak valid</option><option value="spam">Spam</option>
            </select>
          </label>
          <label class="block text-sm font-bold" for="report-note">Catatan internal
            <textarea id="report-note" name="note" rows="3" placeholder="Wajib diisi untuk Tidak valid atau Spam" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal leading-relaxed focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10"></textarea>
          </label>
          <label class="block text-sm font-bold" for="report-response">Tanggapan publik <span class="font-normal text-slate-500">(opsional)</span>
            <textarea id="report-response" name="public_response" rows="3" placeholder="Tulis informasi yang dapat dilihat pelapor" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal leading-relaxed focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10"></textarea>
          </label>
          <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-3 text-sm leading-relaxed transition hover:bg-slate-50 dark:border-white/10 dark:hover:bg-white/5">
            <input type="checkbox" name="block_device" value="1" class="mt-1 h-4 w-4 accent-red-600">
            <span><span class="font-bold">Blokir perangkat 7 hari</span><span class="block text-xs text-slate-500">Gunakan jika laporan ditandai sebagai spam.</span></span>
          </label>
          <button class="btn btn-primary w-full rounded-xl">Simpan perubahan <span aria-hidden="true">→</span></button>
        </form>
      </section>

      <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
        <h2 class="font-extrabold">Riwayat status</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Perubahan tindak lanjut laporan.</p>
        <ol class="mt-5 space-y-4">
          @forelse ($history as $h)
            <li class="relative flex gap-3">
              <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full bg-jl-500 ring-4 ring-jl-100 dark:ring-white/10" aria-hidden="true"></span>
              <div class="min-w-0 flex-1">
                <p class="text-sm font-bold">{{ ucfirst(str_replace('_', ' ', $h->from_status)) }} <span class="text-jl-600">→</span> {{ ucfirst(str_replace('_', ' ', $h->to_status)) }}</p>
                @if ($h->note)<p class="mt-1 break-words text-sm text-slate-600 dark:text-slate-300">{{ $h->note }}</p>@endif
                <p class="mt-1 text-xs text-slate-500">{{ \Illuminate\Support\Carbon::parse($h->created_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
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
