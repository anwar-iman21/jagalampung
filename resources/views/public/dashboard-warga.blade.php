@extends('layouts.app')
@section('title', 'Dashboard Warga')
@section('content')
<div class="mx-auto max-w-7xl px-4 py-10">
  <div class="rounded-3xl bg-gradient-to-br from-jl-900 via-jl-700 to-jl-600 p-6 text-white sm:p-8">
    <p class="text-sm font-bold uppercase tracking-[.14em] text-green-200">Dashboard warga</p>
    <div class="mt-2 flex flex-wrap items-center justify-between gap-4">
      <div><h1 class="text-3xl font-extrabold">Halo, {{ auth()->user()->name }}</h1><p class="mt-2 text-green-50">Pantau laporan yang Anda kirim dari satu tempat.</p></div>
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('lapor') }}" class="btn bg-white text-jl-700">+ Buat laporan</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn border border-red-500 bg-red-600 text-white shadow-sm transition hover:border-red-400 hover:bg-red-700">Keluar</button></form>
      </div>
    </div>
  </div>
  <div class="mt-6 grid gap-4 sm:grid-cols-3">
    @foreach ([['Total laporan',$counts['total']],['Sedang diproses',$counts['diproses']],['Selesai',$counts['selesai']]] as [$label,$value])
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5"><p class="text-3xl font-extrabold text-jl-600">{{ $value }}</p><p class="mt-1 text-sm font-semibold text-slate-600 dark:text-slate-300">{{ $label }}</p></div>
    @endforeach
  </div>
  <section class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="border-b border-slate-100 px-5 py-4 font-extrabold dark:border-white/10">Laporan saya</div>
    @forelse ($reports as $report)
      <a href="{{ route('warga.report', $report) }}" class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 px-5 py-4 transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-jl-500 dark:border-white/10 dark:hover:bg-white/5">
        <div><p class="font-bold">{{ $report->tracking_code }}</p><p class="mt-1 text-sm text-slate-500">{{ ucfirst(str_replace('_', ' ', $report->category)) }} · {{ $report->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p></div>
        <span class="flex items-center gap-2">
          <span class="rounded-full bg-jl-50 px-3 py-1 text-xs font-bold text-jl-700">{{ $report->status?->label() ?? 'Tidak diketahui' }}</span>
          <span class="text-xl text-slate-400" aria-hidden="true">›</span>
        </span>
      </a>
    @empty
      <div class="px-5 py-12 text-center"><p class="font-bold">Belum ada laporan</p><p class="mt-1 text-sm text-slate-500">Laporan yang dibuat saat login akan muncul di sini.</p></div>
    @endforelse
  </section>
  <div class="mt-4">{{ $reports->links() }}</div>
</div>
@endsection
