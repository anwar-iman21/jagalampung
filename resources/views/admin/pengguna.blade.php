@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:py-10">
  @include('admin._nav')
  <div class="mb-7 rounded-3xl bg-gradient-to-br from-jl-900 via-jl-700 to-jl-600 p-6 text-white shadow-lg shadow-jl-900/10 sm:p-8">
    <p class="text-sm font-bold uppercase tracking-[.14em] text-green-200">Pengelolaan akses</p>
    <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
      <div><h1 class="text-3xl font-extrabold sm:text-4xl">Akun Admin</h1><p class="mt-2 text-sm text-green-50 sm:text-base">Kelola akses admin dan penugasan instansi.</p></div>
      <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3"><p class="text-2xl font-extrabold">{{ $users->count() }}</p><p class="text-xs text-green-100">Akun terdaftar</p></div>
    </div>
  </div>
  @if (session('ok'))<p class="mb-5 rounded-2xl border border-jl-100 bg-jl-50 p-4 font-medium text-jl-700" role="status">{{ session('ok') }}</p>@endif
  @if ($errors->any())<div class="shake mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700" role="alert"><p class="font-bold">Akun belum dapat dibuat</p>@foreach ($errors->all() as $e)<p class="mt-1 text-sm">{{ $e }}</p>@endforeach</div>@endif

  <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4 dark:border-white/10 sm:px-6">
      <div><h2 class="font-extrabold sm:text-lg">Daftar akun</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Status akses dan instansi admin</p></div>
      <span class="rounded-full bg-jl-50 px-3 py-1 text-xs font-bold text-jl-700 dark:bg-white/10 dark:text-jl-500">{{ $users->count() }} akun</span>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-[680px] text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-white/5 dark:text-slate-400"><tr><th class="px-5 py-3 sm:px-6">Nama</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Role</th><th class="px-4 py-3">Status</th><th class="px-5 py-3 sm:px-6">Aksi</th></tr></thead>
        <tbody>
          @foreach ($users as $u)
            <tr class="border-t border-slate-100 transition hover:bg-jl-50/60 dark:border-white/10 dark:hover:bg-white/5">
              <td class="px-5 py-4 font-bold sm:px-6">{{ $u->name }}</td><td class="px-4 py-4 text-slate-600 dark:text-slate-300">{{ $u->email }}</td>
              <td class="px-4 py-4"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold dark:bg-white/10">{{ str_replace('_', ' ', $u->role) }}</span></td>
              <td class="px-4 py-4"><span class="inline-flex items-center gap-2 text-xs font-bold {{ $u->is_active ? 'text-jl-700 dark:text-jl-500' : 'text-slate-500' }}"><span class="h-2 w-2 rounded-full {{ $u->is_active ? 'bg-jl-500' : 'bg-slate-400' }}"></span>{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
              <td class="px-5 py-4 sm:px-6">@if ($u->id !== auth()->id())<form method="POST" action="{{ route('admin.users.toggle', $u) }}" onsubmit="return confirm('Ubah status akun ini?')">@csrf<button class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold transition hover:border-jl-300 hover:bg-jl-50 dark:border-white/15 dark:hover:bg-white/10">{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button></form>@else<span class="text-xs text-slate-400">Akun Anda</span>@endif</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>

  <section class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-7">
    <div class="mb-5 flex items-center gap-3">
      <span class="grid h-11 w-11 place-items-center rounded-xl bg-jl-100 text-xl font-bold text-jl-700" aria-hidden="true">+</span>
      <div><h2 class="font-extrabold sm:text-lg">Tambah Admin Instansi</h2><p class="text-sm text-slate-500 dark:text-slate-400">Buat akun baru dan hubungkan dengan instansinya.</p></div>
    </div>
    <form method="POST" action="{{ route('admin.users.store') }}" class="grid gap-4 sm:grid-cols-2">@csrf
      <label class="text-sm font-bold">Nama
        <input name="name" placeholder="Nama lengkap" autocomplete="name" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10 dark:focus:bg-black/20" required>
      </label>
      <label class="text-sm font-bold">Email
        <input type="email" name="email" placeholder="nama@email.com" autocomplete="email" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10 dark:focus:bg-black/20" required>
      </label>
      <label class="text-sm font-bold">Kata sandi <span class="font-normal text-slate-500">(minimal 10 karakter)</span>
        <input type="password" name="password" placeholder="Buat kata sandi" autocomplete="new-password" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:bg-white focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-black/10 dark:focus:bg-black/20" required>
      </label>
      <label class="text-sm font-bold">Instansi
        <select name="agency_id" class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-normal focus:border-jl-500 focus:ring-4 focus:ring-jl-100 dark:border-white/15 dark:bg-[#0b1410]" required>@foreach ($agencies as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select>
      </label>
      <button class="btn btn-primary w-full rounded-xl sm:col-span-2">Buat Akun <span aria-hidden="true">→</span></button>
    </form>
  </section>
</div>
@endsection
