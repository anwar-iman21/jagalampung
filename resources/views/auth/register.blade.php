@extends('layouts.app')
@section('title', 'Daftar Akun Warga')
@section('content')
<div class="mx-auto max-w-lg px-4 py-10 sm:py-16">
  <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl sm:p-9 dark:border-white/10 dark:bg-white/5">
    <p class="text-sm font-bold uppercase tracking-[.14em] text-jl-600">Akun warga</p>
    <h1 class="mt-2 text-3xl font-extrabold">Daftar JagaLampung</h1>
    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Simpan semua laporan Anda dalam satu akun.</p>
    <form method="POST" action="{{ route('register.post') }}" class="mt-6 space-y-4">@csrf
      <div>
        <label for="name" class="mb-2 block text-sm font-bold">Nama lengkap</label>
        <input id="name" name="name" value="{{ old('name') }}" required aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" aria-describedby="name-error" @class(['w-full rounded-xl border px-4 py-3.5 transition dark:bg-black/10', 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500' => $errors->has('name'), 'border-slate-300 dark:border-white/15' => ! $errors->has('name')])>
        @error('name')<p id="name-error" class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="email" class="mb-2 block text-sm font-bold">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" aria-describedby="email-error" @class(['w-full rounded-xl border px-4 py-3.5 transition dark:bg-black/10', 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500' => $errors->has('email'), 'border-slate-300 dark:border-white/15' => ! $errors->has('email')])>
        @error('email')<p id="email-error" class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="nik" class="mb-2 block text-sm font-bold">NIK <span class="font-normal text-slate-500">(Nomor Induk Kependudukan)</span></label>
        <input id="nik" type="text" name="nik" value="{{ $oldNik }}" inputmode="numeric" pattern="[0-9]{16}" minlength="16" maxlength="16" autocomplete="off" placeholder="Masukkan 16 digit NIK" required aria-invalid="{{ $errors->has('nik') ? 'true' : 'false' }}" aria-describedby="nik-help{{ $errors->has('nik') ? ' nik-error' : '' }}" @class(['w-full rounded-xl border px-4 py-3.5 tracking-wider transition placeholder:tracking-normal dark:bg-black/10', 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500' => $errors->has('nik'), 'border-slate-300 focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15' => ! $errors->has('nik')])>
        <p id="nik-help" class="mt-2 text-xs leading-relaxed text-slate-500 dark:text-slate-400">Tepat 16 digit angka sesuai KTP. NIK dijaga sebagai data pribadi dan tidak ditampilkan pada laporan.</p>
        @error('nik')<p id="nik-error" class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="password" class="mb-2 block text-sm font-bold">Kata sandi</label>
        <input id="password" type="password" name="password" required autocomplete="new-password" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" aria-describedby="password-error" @class(['w-full rounded-xl border px-4 py-3.5 transition dark:bg-black/10', 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500' => $errors->has('password'), 'border-slate-300 dark:border-white/15' => ! $errors->has('password')])>
        @error('password')<p id="password-error" class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="password_confirmation" class="mb-2 block text-sm font-bold">Ulangi kata sandi</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}" aria-describedby="password-confirmation-error" @class(['w-full rounded-xl border px-4 py-3.5 transition dark:bg-black/10', 'border-red-500 bg-red-50 focus:border-red-500 focus:ring-4 focus:ring-red-100 dark:border-red-500' => $errors->has('password_confirmation'), 'border-slate-300 dark:border-white/15' => ! $errors->has('password_confirmation')])>
        @error('password_confirmation')<p id="password-confirmation-error" class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
      </div>
      @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700" role="alert">
          Periksa kolom berwarna merah. Data lain tetap tersimpan; kata sandi perlu diisi ulang demi keamanan.
        </div>
      @endif
      <button class="btn btn-primary w-full rounded-xl">Buat akun</button>
    </form>
  </div>
</div>
@endsection
