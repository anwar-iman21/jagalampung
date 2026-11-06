@extends('layouts.app')
@section('title', 'Lapor Cepat')
@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:py-12" x-data="lapor">
  <div class="mb-6 rounded-3xl bg-gradient-to-br from-jl-900 via-jl-700 to-jl-600 p-6 text-white shadow-lg shadow-jl-900/10 sm:mb-8 sm:p-8">
    <p class="text-sm font-bold uppercase tracking-[.14em] text-green-200">Bersama jaga Lampung</p>
    <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Lapor Cepat</h1>
    <p class="mt-3 max-w-xl text-sm leading-relaxed text-green-50 sm:text-base">Bagikan informasi kejadian agar dapat segera ditinjau. Isi beberapa langkah singkat di bawah.</p>
    <a href="tel:112" class="mt-5 inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 font-bold text-white transition hover:bg-white/20 sm:w-auto">
      <span aria-hidden="true">☎</span> Darurat? Telepon 112
    </a>
  </div>

  @if ($errors->any())
    <div class="shake mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
      <p class="font-bold">Laporan belum dapat dikirim</p>
      <ul class="mt-2 list-inside list-disc space-y-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('lapor.store') }}" enctype="multipart/form-data" class="space-y-5" @submit="submit($event)">
    @csrf
    <input type="hidden" name="idem" :value="idem">
    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 dark:border-white/10 dark:bg-white/5">
      <div class="mb-5 flex items-center gap-3">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-jl-100 font-extrabold text-jl-700">1</span>
        <div><h2 class="font-extrabold sm:text-lg">Foto kejadian</h2><p class="text-sm text-slate-500 dark:text-slate-400">Tambahkan foto agar kejadian mudah dikenali.</p></div>
      </div>
      <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-4 sm:p-5 dark:border-white/20 dark:bg-black/10">
        <p class="text-sm font-bold">Ambil foto langsung atau pilih dari perangkat</p>
        <p class="mt-1 text-xs leading-relaxed text-slate-500">Kamera tersedia di browser HP dan desktop. Izinkan akses kamera saat diminta.</p>
        <input x-ref="photoInput" type="file" name="photo" accept="image/*" aria-label="Pilih foto laporan dari perangkat" @change="pick($event)" class="sr-only">
        <div class="mt-4 flex flex-col gap-2 sm:flex-row">
          <button type="button" @click="openCamera" class="btn min-h-11 w-full rounded-xl bg-jl-700 px-4 text-sm text-white transition hover:bg-jl-600 sm:w-auto"><span aria-hidden="true">◎</span> Buka kamera</button>
          <button type="button" @click="$refs.photoInput.click()" class="btn min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-700 hover:bg-slate-50 sm:w-auto dark:border-white/15 dark:bg-white/5 dark:text-white"><span aria-hidden="true">↑</span> Pilih foto</button>
          <button x-show="preview" x-cloak type="button" @click="openCamera" class="btn min-h-11 w-full rounded-xl border border-jl-200 px-4 text-sm text-jl-700 hover:bg-jl-50 sm:w-auto dark:border-white/15 dark:text-jl-500"><span aria-hidden="true">↻</span> Ambil ulang</button>
        </div>
        <div x-show="cameraOpen" x-cloak class="mt-4 overflow-hidden rounded-2xl bg-slate-950">
          <div x-show="cameraBusy" class="grid min-h-24 place-items-center px-4 text-sm font-medium text-white" role="status">Meminta izin dan menyiapkan kamera…</div>
          <video x-ref="cameraVideo" x-show="!cameraBusy && !cameraError" autoplay muted playsinline class="max-h-[65vh] min-h-48 w-full object-contain"></video>
          <div class="flex flex-col gap-2 bg-slate-900 p-3 sm:flex-row sm:justify-end">
            <button type="button" @click="stopCamera" class="min-h-11 rounded-xl border border-white/20 px-4 text-sm font-bold text-white hover:bg-white/10">Batal</button>
            <button type="button" @click="capturePhoto" :disabled="cameraBusy" class="min-h-11 rounded-xl bg-white px-5 text-sm font-extrabold text-jl-700 hover:bg-jl-50 disabled:cursor-wait disabled:opacity-60"><span aria-hidden="true">●</span> Ambil foto</button>
          </div>
        </div>
        <p x-show="cameraError" x-cloak x-text="cameraError" class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700" role="alert"></p>
        <p x-show="photoBusy" x-cloak class="mt-3 text-sm font-semibold text-jl-700" role="status">Memproses foto…</p>
        <p x-show="photoError" x-cloak x-text="photoError" class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700" role="alert"></p>
        <img x-show="preview" x-cloak x-transition :src="preview" alt="Pratinjau foto kejadian" class="mt-4 max-h-72 w-full rounded-xl bg-slate-200 object-contain shadow-sm">
        <p class="mt-3 text-xs text-slate-500">Foto akan dikecilkan otomatis agar lebih cepat dikirim.</p>
      </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 dark:border-white/10 dark:bg-white/5">
      <div class="mb-4 flex items-center gap-3">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-jl-100 font-extrabold text-jl-700">2</span>
        <div><h2 class="font-extrabold sm:text-lg">Lokasi kejadian</h2><p class="text-sm text-slate-500 dark:text-slate-400">Lokasi membantu petugas menemukan kejadian.</p></div>
      </div>
      <div class="rounded-2xl bg-jl-50 p-4 dark:bg-white/10">
        <div class="flex items-start gap-3">
          <span class="mt-0.5 shrink-0 text-lg text-jl-700 dark:text-jl-500" aria-hidden="true">⌖</span>
          <p class="text-sm font-semibold leading-relaxed text-jl-700 dark:text-jl-500" x-text="geo" role="status"></p>
        </div>
        <button type="button" @click="startLocation" class="ml-8 mt-2 text-xs font-bold text-jl-700 underline underline-offset-2 hover:text-jl-500 dark:text-jl-500">Perbarui lokasi GPS</button>
        <p class="ml-8 mt-1 text-xs leading-relaxed text-slate-500">GPS diperbarui otomatis. Akurasi bergantung pada perangkat dan sinyal; untuk hasil terbaik, aktifkan lokasi presisi dan berada di area terbuka.</p>
      </div>
      <input type="hidden" name="lat" x-model="lat"><input type="hidden" name="lng" x-model="lng"><input type="hidden" name="accuracy" x-model="acc">
      <details class="mt-4 rounded-xl border border-slate-200 dark:border-white/10" x-bind:open="manualLocation || (!lat && !lng)">
        <summary class="cursor-pointer rounded-xl px-4 py-3 text-sm font-bold text-jl-700 hover:bg-slate-50 dark:text-jl-500 dark:hover:bg-white/5">Isi koordinat manual</summary>
        <div class="grid gap-3 p-4 pt-1 sm:grid-cols-2">
          <label class="text-sm font-semibold">Latitude<input class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10" placeholder="Contoh: -5.429" x-model="lat" @input="setManualLocation" inputmode="decimal"></label>
          <label class="text-sm font-semibold">Longitude<input class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-3 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10" placeholder="Contoh: 105.262" x-model="lng" @input="setManualLocation" inputmode="decimal"></label>
        </div>
      </details>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 dark:border-white/10 dark:bg-white/5">
      <div class="mb-4 flex items-center gap-3">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-jl-100 font-extrabold text-jl-700">3</span>
        <div><h2 class="font-extrabold sm:text-lg">Pilih kategori</h2><p class="text-sm text-slate-500 dark:text-slate-400">Pilih jenis kejadian yang paling sesuai.</p></div>
      </div>
      <fieldset>
        <legend class="sr-only">Kategori laporan</legend>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
          @foreach ($categories as $k => $t)
            <label class="hover-lift flex min-h-14 cursor-pointer items-center gap-3 rounded-2xl border-2 border-slate-200 bg-white p-4 font-semibold transition has-[:checked]:border-jl-500 has-[:checked]:bg-jl-50 has-[:checked]:text-jl-700 dark:border-white/15 dark:bg-black/10 dark:has-[:checked]:bg-white/10">
              <input type="radio" name="category" value="{{ $k }}" class="h-4 w-4 accent-green-700" required @checked($selected === $k)>{{ $t }}
            </label>
          @endforeach
        </div>
      </fieldset>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 dark:border-white/10 dark:bg-white/5">
      <div class="mb-5 flex items-center gap-3">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-jl-100 font-extrabold text-jl-700">4</span>
        <div><h2 class="font-extrabold sm:text-lg">Detail laporan</h2><p class="text-sm text-slate-500 dark:text-slate-400">Informasi tambahan bersifat opsional.</p></div>
      </div>
      <label class="block"><span class="text-sm font-bold">Keterangan <span class="font-normal text-slate-500">(opsional)</span></span>
        <textarea name="description" rows="4" maxlength="1000" placeholder="Ceritakan detail kejadian, patokan lokasi, atau informasi lain yang membantu." class="mt-2 w-full resize-y rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 leading-relaxed transition placeholder:text-slate-400 focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10 dark:focus:bg-black/20">{{ old('description') }}</textarea>
      </label>
      <label class="mt-5 block"><span class="text-sm font-bold">Kontak <span class="font-normal text-slate-500">(opsional)</span></span>
        <input name="contact" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 transition placeholder:text-slate-400 focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10 dark:focus:bg-black/20" placeholder="No. WA / email, agar admin bisa menghubungi">
        <span class="mt-2 block text-xs leading-relaxed text-slate-500">Disimpan terenkripsi, hanya dilihat admin berwenang.</span>
      </label>
    </section>

    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-relaxed text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-200">
      Laporan palsu dapat ditindak sesuai ketentuan yang berlaku.
    </div>
    <button class="btn btn-primary w-full rounded-2xl py-4 text-base shadow-lg shadow-green-700/20 sm:text-lg" :disabled="busy || photoBusy">
      <span x-show="!busy">Kirim Laporan <span aria-hidden="true">→</span></span>
      <span x-show="busy" x-cloak>Mengirim…</span>
    </button>
  </form>
</div>
@endsection
