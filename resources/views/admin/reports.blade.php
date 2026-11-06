@extends('layouts.app')
@section('title', 'Antrean Laporan')
@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:py-10">
  @include('admin._nav')
  <div class="mb-7 rounded-3xl bg-gradient-to-br from-jl-900 via-jl-700 to-jl-600 p-6 text-white shadow-lg shadow-jl-900/10 sm:p-8">
    <div class="flex flex-wrap items-start justify-between gap-5">
      <div>
        <p class="text-sm font-bold uppercase tracking-[.14em] text-green-200">Panel pengelolaan</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Antrean Laporan</h1>
        <p class="mt-2 text-sm text-green-50 sm:text-base">Tinjau, verifikasi, dan pantau tindak lanjut laporan warga.</p>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">@csrf<button class="min-h-11 w-full rounded-xl border border-red-200 bg-red-600 px-5 text-sm font-bold text-white shadow-sm transition hover:border-red-300 hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white sm:w-auto">Keluar</button></form>
    </div>
  </div>

  <div class="grid gap-4 sm:grid-cols-3">
    @foreach ([['Perlu verifikasi',$counts['baru']],['Diproses',$counts['diproses']],['Selesai',$counts['selesai']]] as [$l,$v])
      <div class="hover-lift rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
        <p class="text-3xl font-extrabold text-jl-600">{{ $v }}</p><p class="mt-1 text-sm font-semibold text-slate-600 dark:text-slate-300">{{ $l }}</p>
      </div>
    @endforeach
  </div>

  <form class="mt-6 grid gap-3 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-[minmax(12rem,1.4fr)_1fr_1fr_auto] dark:border-white/10 dark:bg-white/5" method="GET">
    <label class="sr-only" for="report-search">Cari kode laporan</label>
    <input id="report-search" name="q" value="{{ request('q') }}" placeholder="Cari kode laporan" class="min-h-11 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10">
    <label class="sr-only" for="report-status">Filter status</label>
    <select id="report-status" name="status" class="min-h-11 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-[#0b1410]">
      <option value="">Semua status</option>
      @foreach (['dilaporkan','diproses','selesai','ditolak'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach
    </select>
    <label class="sr-only" for="report-verification">Filter verifikasi</label>
    <select id="report-verification" name="verification" class="min-h-11 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-[#0b1410]">
      <option value="">Semua verifikasi</option>
      @foreach (['belum','valid','tidak_valid','spam'] as $s)<option value="{{ $s }}" @selected(request('verification')===$s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>@endforeach
    </select>
    <button class="btn btn-primary min-h-11 w-full rounded-xl lg:w-auto">Terapkan filter</button>
  </form>

  <section class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4 dark:border-white/10 sm:px-6">
      <div><h2 class="font-extrabold">Daftar laporan</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih kode laporan untuk melihat detail dan tindak lanjut.</p></div>
      <span class="rounded-full bg-jl-50 px-3 py-1 text-xs font-bold text-jl-700 dark:bg-white/10 dark:text-jl-500">{{ $reports->total() }} laporan</span>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-[900px] text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-white/5 dark:text-slate-400">
          <tr><th class="px-5 py-3 sm:px-6">Kode</th><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Prioritas</th><th class="px-4 py-3">Skor</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Verifikasi</th><th class="px-5 py-3 sm:px-6">Waktu (WIB)</th></tr>
        </thead>
        <tbody>
          @forelse ($reports as $r)
            <tr class="border-t border-slate-100 transition hover:bg-jl-50/60 dark:border-white/10 dark:hover:bg-white/5">
              <td class="px-5 py-4 sm:px-6"><a class="font-bold text-jl-700 underline decoration-jl-200 underline-offset-4 hover:text-jl-500 dark:text-jl-500" href="{{ route('admin.report', $r) }}">{{ $r->tracking_code }}</a></td>
              <td class="px-4 py-4">{{ ucfirst(str_replace('_', ' ', $r->category)) }}</td>
              <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $r->priority === 'tinggi' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-slate-300' }}">{{ ucfirst($r->priority) }}</span></td>
              <td class="px-4 py-4"><span class="font-bold">{{ $r->trust_score }}</span><span class="ml-1 text-xs text-slate-500">({{ $r->trust_label }})</span></td>
              <td class="px-4 py-4">{{ $r->status->label() }}</td>
              <td class="px-4 py-4">{{ ucfirst(str_replace('_', ' ', $r->verification)) }}</td>
              <td class="whitespace-nowrap px-5 py-4 text-slate-600 dark:text-slate-300 sm:px-6">{{ $r->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
            </tr>
          @empty
            <tr><td colspan="7" class="px-5 py-12 text-center sm:px-6">
              <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-xl dark:bg-white/10" aria-hidden="true">⌕</span>
              <p class="mt-3 font-bold">Belum ada laporan</p><p class="mt-1 text-sm text-slate-500">Laporan yang sesuai filter akan muncul di sini.</p>
            </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
  <div class="mt-4">{{ $reports->links() }}</div>
</div>
@endsection
