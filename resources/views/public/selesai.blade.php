@extends('layouts.app')
@section('title', 'Laporan terkirim')
@section('content')
<div class="mx-auto max-w-xl px-4 py-12 text-center" x-data="{ ok: false }">
  <svg class="draw-check mx-auto" width="90" height="90" viewBox="0 0 52 52" fill="none" stroke="#15803d" stroke-width="4" stroke-linecap="round"><circle cx="26" cy="26" r="23" opacity=".25"/><path d="m14 27 8 8 16-18"/></svg>
  <h1 class="mt-4 text-2xl font-extrabold">Laporan terkirim</h1>
  <p class="mt-2">Kode lacak Anda:</p>
  <p class="mt-1 text-3xl font-extrabold tracking-wider text-jl-600">{{ $report->tracking_code }}</p>
  @if ($token)
    <p class="mt-4">Token rahasia (hanya tampil sekali, simpan!):</p>
    <p class="mt-1 font-mono text-xl font-bold">{{ $token }}</p>
    <button class="btn btn-primary mt-4" @click="navigator.clipboard.writeText('{{ $report->tracking_code }} / {{ $token }}'); ok = true">
      <span x-text="ok ? '✓ Tersalin' : 'Salin kode & token'"></span></button>
  @endif
  <p class="mt-6 text-sm text-slate-600">Laporan akan diverifikasi admin sebelum tampil publik.</p>
  @if (in_array($report->category, ['kecelakaan', 'bencana']))
    <div class="mt-6 rounded-2xl bg-red-50 p-4 text-red-700">Jika ada korban atau situasi mengancam jiwa, segera hubungi <a class="font-bold underline" href="tel:112">112</a>.</div>
  @endif
  <a href="{{ route('lacak') }}" class="btn mt-6 border">Lacak Laporan</a>
</div>
@endsection
