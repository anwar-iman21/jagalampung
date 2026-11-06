@extends('layouts.app')
@section('title', 'Kelola Darurat')
@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:py-10">
  @include('admin._nav')
  <div class="mb-7 rounded-3xl bg-gradient-to-br from-red-800 via-red-700 to-rose-600 p-6 text-white shadow-lg shadow-red-900/10 sm:p-8">
    <p class="text-sm font-bold uppercase tracking-[.14em] text-red-100">Pusat kendali</p>
    <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Informasi Darurat</h1>
    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-red-50 sm:text-base">Kelola nomor penting, titik evakuasi, dan peringatan yang ditampilkan kepada warga.</p>
  </div>
  @if (session('ok'))<p class="mb-5 rounded-2xl border border-jl-100 bg-jl-50 p-4 font-medium text-jl-700" role="status">{{ session('ok') }}</p>@endif
  @if ($errors->any())<div class="shake mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700" role="alert"><p class="font-bold">Informasi belum dapat disimpan</p>@foreach ($errors->all() as $e)<p class="mt-1 text-sm">{{ $e }}</p>@endforeach</div>@endif
  <div class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm leading-relaxed text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-200">
    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-amber-200 font-extrabold text-amber-900" aria-hidden="true">!</span>
    <p>Gunakan sumber resmi untuk nomor dan informasi darurat. Sertakan sumber serta tanggal verifikasi jika tersedia.</p>
  </div>
  <div class="grid gap-5 lg:grid-cols-2">
    <form method="POST" action="{{ route('admin.darurat.contact') }}" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">@csrf
      <div class="mb-5 flex items-center gap-3"><span class="grid h-11 w-11 place-items-center rounded-xl bg-red-100 text-xl text-red-700" aria-hidden="true">☎</span><div><h2 class="font-extrabold sm:text-lg">Nomor penting</h2><p class="text-sm text-slate-500 dark:text-slate-400">Kontak layanan resmi</p></div></div>
      <div class="space-y-4">
        <label class="block text-sm font-bold">Nama layanan<input name="name" placeholder="Contoh: Layanan Darurat" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10" required></label>
        <label class="block text-sm font-bold">Nomor telepon<input name="number" placeholder="Contoh: 112" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10" required></label>
        <label class="block text-sm font-bold">Cakupan wilayah<input name="scope" value="daerah" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10"></label>
        <label class="block text-sm font-bold">URL sumber resmi <span class="font-normal text-slate-500">(opsional)</span><input type="url" name="source_url" placeholder="https://" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10"></label>
        <label class="block text-sm font-bold">Tanggal verifikasi <span class="font-normal text-slate-500">(opsional)</span><input type="date" name="verified_at" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10"></label>
      </div>
      <button class="btn btn-primary mt-5 w-full rounded-xl">Simpan nomor <span aria-hidden="true">→</span></button>
    </form>

    <form method="POST" action="{{ route('admin.darurat.evac') }}" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">@csrf
      <div class="mb-5 flex items-center gap-3"><span class="grid h-11 w-11 place-items-center rounded-xl bg-sky-100 text-xl text-sky-700" aria-hidden="true">⌖</span><div><h2 class="font-extrabold sm:text-lg">Titik evakuasi</h2><p class="text-sm text-slate-500 dark:text-slate-400">Lokasi aman untuk warga</p></div></div>
      <div class="space-y-4">
        <label class="block text-sm font-bold">Nama lokasi<input name="name" placeholder="Nama titik evakuasi" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10" required></label>
        <label class="block text-sm font-bold">Alamat<input name="address" placeholder="Alamat lengkap" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10"></label>
        <div class="grid grid-cols-2 gap-3">
          <label class="block text-sm font-bold">Latitude<input name="lat" placeholder="-5.429" inputmode="decimal" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10" required></label>
          <label class="block text-sm font-bold">Longitude<input name="lng" placeholder="105.262" inputmode="decimal" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10" required></label>
        </div>
        <label class="block text-sm font-bold">Kapasitas <span class="font-normal text-slate-500">(opsional)</span><input type="number" name="capacity" min="0" placeholder="Jumlah orang" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10"></label>
        <label class="block text-sm font-bold">Sumber data <span class="font-normal text-slate-500">(opsional)</span><input name="source" placeholder="Nama instansi atau URL sumber" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10"></label>
      </div>
      <button class="btn btn-primary mt-5 w-full rounded-xl">Simpan titik evakuasi <span aria-hidden="true">→</span></button>
    </form>

    <form method="POST" action="{{ route('admin.darurat.alert') }}" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6 lg:col-span-2">@csrf
      <div class="mb-5 flex items-center gap-3"><span class="grid h-11 w-11 place-items-center rounded-xl bg-amber-100 text-xl text-amber-700" aria-hidden="true">!</span><div><h2 class="font-extrabold sm:text-lg">Peringatan wilayah</h2><p class="text-sm text-slate-500 dark:text-slate-400">Terbitkan informasi peringatan secara manual.</p></div></div>
      <div class="grid gap-4 sm:grid-cols-2">
        <label class="block text-sm font-bold sm:col-span-2">Judul peringatan<input name="title" placeholder="Contoh: Waspada banjir di wilayah ..." class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10" required></label>
        <label class="block text-sm font-bold sm:col-span-2">Isi peringatan<textarea name="body" rows="4" placeholder="Tuliskan informasi dan arahan yang perlu diketahui warga." class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal leading-relaxed focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10" required></textarea></label>
        <label class="block text-sm font-bold">Tingkat peringatan<select name="severity" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-[#0b1410]"><option value="info">Info</option><option value="waspada">Waspada</option><option value="bahaya">Bahaya</option></select></label>
        <label class="block text-sm font-bold">Berlaku sampai <span class="font-normal text-slate-500">(opsional)</span><input type="datetime-local" name="valid_until" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10"></label>
      </div>
      <button class="btn btn-primary mt-5 w-full rounded-xl sm:w-auto sm:min-w-56">Terbitkan peringatan <span aria-hidden="true">→</span></button>
    </form>
  </div>
</div>
@endsection
