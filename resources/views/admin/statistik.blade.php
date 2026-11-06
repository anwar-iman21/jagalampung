@extends('layouts.app')
@section('title', 'Statistik')
@section('content')
<div class="mx-auto max-w-7xl px-4 py-8">
  @include('admin._nav')
  <div class="flex items-center justify-between"><h1 class="text-2xl font-extrabold">Statistik & Rekap</h1>
    <a class="btn btn-primary min-h-10" href="{{ route('admin.stats.csv') }}">Ekspor CSV</a></div>
  <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ([['Total laporan',$s['total']],['Spam / tidak valid',$s['spam_pct'].'%'],['Rata-rata respons',$s['mean_min'] === null ? '—' : round($s['mean_min']).' mnt'],['Median respons',$s['median_min'] === null ? '—' : round($s['median_min']).' mnt']] as [$l,$v])
      <div class="hover-lift rounded-2xl bg-white p-5 shadow dark:bg-white/5"><p class="text-3xl font-extrabold text-jl-600">{{ $v }}</p><p>{{ $l }}</p></div>
    @endforeach
  </div>
  @if ($s['total'] === 0)<p class="mt-6 text-slate-500">Belum ada data. Grafik muncul setelah ada laporan.</p>@endif
  <div class="mt-6 grid gap-6 lg:grid-cols-2">
    <div class="rounded-2xl bg-white p-4 shadow dark:bg-white/5"><h2 class="font-bold">Per kategori</h2><canvas id="c1" height="200"></canvas></div>
    <div class="rounded-2xl bg-white p-4 shadow dark:bg-white/5"><h2 class="font-bold">30 hari terakhir (WIB)</h2><canvas id="c2" height="200"></canvas></div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const green = '#15803d';
new Chart(document.getElementById('c1'), { type: 'bar', data: { labels: @json($s['per_category']->keys()), datasets: [{ data: @json($s['per_category']->values()), backgroundColor: green }] }, options: { plugins: { legend: { display: false } }, animation: { duration: 900 } } });
new Chart(document.getElementById('c2'), { type: 'line', data: { labels: @json($s['daily']->keys()), datasets: [{ data: @json($s['daily']->values()), borderColor: green, tension: .3 }] }, options: { plugins: { legend: { display: false } }, animation: { duration: 900 } } });
</script>
@endsection
