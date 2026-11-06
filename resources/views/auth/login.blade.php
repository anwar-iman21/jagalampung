@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:py-16">
  <div class="mx-auto grid max-w-4xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5 dark:border-white/10 dark:bg-white/5 md:grid-cols-2">
    <div class="relative isolate flex min-h-52 flex-col justify-between overflow-hidden bg-gradient-to-br from-jl-900 via-jl-700 to-jl-600 p-6 text-white sm:p-8 md:min-h-[30rem]">
      <div class="absolute -right-14 -top-14 -z-10 h-56 w-56 rounded-full bg-green-300/20 blur-3xl"></div>
      <a href="{{ route('home') }}" class="flex items-center gap-3 text-lg font-extrabold">
        <svg width="32" height="32" viewBox="0 0 32 32" aria-hidden="true"><path d="M16 2 4 7v9c0 7 5 12 12 14 7-2 12-7 12-14V7z" fill="#fff"/><path d="m10 16 4 4 8-9" stroke="#15803d" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
        JagaLampung
      </a>
      <div class="mt-8 md:mt-0">
        <p class="text-sm font-bold uppercase tracking-[.16em] text-green-200">Ruang pengelola</p>
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">Selamat datang</h1>
        <p class="mt-3 max-w-sm text-sm leading-relaxed text-green-50 sm:text-base">Masuk untuk mengelola laporan dan informasi JagaLampung.</p>
      </div>
      <p class="mt-8 text-xs text-green-100/80">Akses hanya untuk admin yang berwenang.</p>
    </div>

    <div class="flex items-center p-6 sm:p-9 md:p-10">
      <div class="w-full">
        <p class="text-sm font-bold uppercase tracking-[.14em] text-jl-600">Admin</p>
        <h2 class="mt-2 text-2xl font-extrabold sm:text-3xl">Masuk akun</h2>
        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Gunakan email dan kata sandi admin Anda.</p>

        @error('email')
          <div class="shake mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-300" role="alert">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('login.post') }}" class="mt-6 space-y-5">@csrf
          <div>
            <label for="login-email" class="mb-2 block text-sm font-bold">Email</label>
            <input id="login-email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="username" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 transition placeholder:text-slate-400 focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10 dark:focus:bg-black/20" required autofocus>
          </div>
          <div>
            <label for="login-password" class="mb-2 block text-sm font-bold">Kata sandi</label>
            <input id="login-password" type="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 transition placeholder:text-slate-400 focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10 dark:focus:bg-black/20" required>
          </div>
          <button class="btn btn-primary w-full rounded-xl py-3.5 text-base shadow-md shadow-green-700/20">Masuk <span aria-hidden="true">→</span></button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
