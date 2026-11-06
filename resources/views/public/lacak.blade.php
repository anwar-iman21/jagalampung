@extends('layouts.app')
@section('title', 'Lacak Laporan')
@section('content')
<div class="mx-auto max-w-2xl px-4 py-10 sm:py-14">
  <div class="mx-auto max-w-xl">
    <div class="mb-6 flex items-center gap-4">
      <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-jl-100 text-jl-700">
        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
          <circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 4.5 4.5M8 10.8h5.6M10.8 8v5.6"></path>
        </svg>
      </div>
      <div>
        <p class="text-sm font-bold uppercase tracking-[.14em] text-jl-600">Pantau perkembangan</p>
        <h1 class="mt-1 text-2xl font-extrabold sm:text-3xl">Lacak Laporan</h1>
      </div>
    </div>
    <p class="mb-6 max-w-lg text-sm leading-relaxed text-slate-600 sm:text-base dark:text-slate-300">Masukkan kode lacak dan token rahasia yang Anda simpan saat mengirim laporan.</p>

    <form method="POST" action="{{ route('lacak.cek') }}" class="space-y-5 rounded-3xl border border-slate-200 bg-white p-5 shadow-lg shadow-slate-900/5 sm:p-7 dark:border-white/10 dark:bg-white/5">@csrf
      <div>
        <label for="tracking-code" class="mb-2 block text-sm font-bold">Kode lacak</label>
        <input id="tracking-code" name="code" value="{{ old('code') }}" placeholder="Contoh: JL-2026-XXXXXX" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-base transition placeholder:text-slate-400 focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10 dark:focus:bg-black/20" autocomplete="off" required>
        @error('code')<p class="shake mt-2 text-sm font-medium text-red-600" role="alert">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="tracking-token" class="mb-2 block text-sm font-bold">Token rahasia</label>
        <input id="tracking-token" name="token" placeholder="Masukkan token rahasia" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-base transition placeholder:text-slate-400 focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10 dark:focus:bg-black/20" autocomplete="off" required>
        @error('token')<p class="shake mt-2 text-sm font-medium text-red-600" role="alert">{{ $message }}</p>@enderror
      </div>
      <button class="btn btn-primary w-full rounded-xl py-3 text-base shadow-md shadow-green-700/20">Cek Status <span aria-hidden="true">→</span></button>
      <p class="text-center text-xs leading-relaxed text-slate-500 dark:text-slate-400">Token diperlukan untuk menjaga informasi laporan Anda tetap pribadi.</p>
    </form>
  </div>
  @if ($report)
    @php $steps = ['dilaporkan','diproses','selesai']; $cur = array_search($report->status->value, $steps); @endphp
    <div class="reveal is-in mt-8 rounded-3xl bg-white p-6 shadow dark:bg-white/5">
      <p class="font-bold">{{ $report->tracking_code }}</p>
      @if ($report->status->value === 'ditolak')
        <p class="mt-3 rounded-xl bg-slate-100 p-3 text-slate-700">{{ $report->status->label() }}</p>
      @else
        <ol class="mt-4 space-y-3">
          @foreach ($steps as $i => $s)
            <li class="flex items-center gap-3"><span class="grid h-8 w-8 place-items-center rounded-full {{ $i <= $cur ? 'bg-jl-600 text-white' : 'bg-slate-200' }}">{{ $i <= $cur ? '✓' : $i + 1 }}</span><span class="{{ $i === $cur ? 'font-extrabold' : '' }}">{{ ucfirst($s) }}</span></li>
          @endforeach
        </ol>
      @endif
    </div>
  @endif
</div>
@endsection
