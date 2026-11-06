@extends('layouts.app')
@section('title', 'Peta Sebaran')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<div class="mx-auto max-w-7xl px-4 py-6 sm:py-8">
  <h1 class="text-2xl font-extrabold sm:text-3xl">Peta Sebaran Laporan</h1>
  <p class="text-sm text-slate-500">Hanya laporan yang sudah diverifikasi admin. Lokasi diperhalus demi privasi.</p>
  <div id="map" class="mt-4 h-[calc(100dvh-17rem)] min-h-[24rem] w-full overflow-hidden rounded-2xl shadow sm:h-[70vh] sm:min-h-[28rem]"></div>
  <p id="empty" class="mt-3 hidden text-center text-slate-500">Belum ada laporan terverifikasi.</p>
</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([-5.4295, 105.2610], 12);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
fetch('{{ route('peta.data') }}').then(r => r.json()).then(rows => {
  if (!rows.length) document.getElementById('empty').classList.remove('hidden');
  rows.forEach(r => { const m = L.marker([r.lat, r.lng]).addTo(map); const d = document.createElement('div');
    d.textContent = `${r.category} • ${r.status} • ${r.time}`; m.bindPopup(d); });
});
</script>
@endsection
