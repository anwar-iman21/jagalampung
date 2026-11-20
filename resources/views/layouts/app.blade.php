<!DOCTYPE html>
<html lang="id" x-data="theme">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'JagaLampung') — Lapor Cepat, Warga Aman</title>
<meta name="description" content="JagaLampung: platform pelaporan warga dan informasi darurat Provinsi Lampung.">
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=outfit:500,600,700,800|plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden" x-data="nav">
<div class="fixed inset-x-0 top-0 z-[60] h-1"><div class="h-full bg-jl-500 transition-[width]" :style="`width:${progress}%`"></div></div>

<header class="fixed inset-x-0 top-0 z-50 bg-white shadow-sm transition dark:bg-[#0b1410]" :class="scrolled ? 'shadow-md' : ''">
  <nav class="site-header-row mx-auto flex h-16 max-w-7xl items-center justify-between px-4" aria-label="Navigasi utama">
    <a href="{{ route('home') }}" class="site-header-brand flex items-center gap-2 text-xl font-extrabold text-jl-700 dark:text-jl-500">
      <svg width="30" height="30" viewBox="0 0 32 32" aria-hidden="true"><path d="M16 2 4 7v9c0 7 5 12 12 14 7-2 12-7 12-14V7z" fill="#15803d"/><path d="m10 16 4 4 8-9" stroke="#fff" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
      JagaLampung
    </a>
    <div class="hidden items-center gap-6 xl:flex">
      @foreach ([['Cara Kerja','/#cara'],['Kategori','/#kategori'],['Peta',route('peta')],['Darurat',route('darurat')],['FAQ','/#faq'],['Lacak',route('lacak')]] as [$t,$u])
        <a href="{{ $u }}" class="group relative py-1 font-medium">{{ $t }}<span class="absolute inset-x-0 -bottom-0.5 h-0.5 origin-left scale-x-0 bg-jl-500 transition group-hover:scale-x-100"></span></a>
      @endforeach
    </div>
    <div class="site-header-actions ml-3 flex shrink-0 items-center gap-2 sm:ml-6 sm:gap-3">
      @guest
        <a href="{{ route('login') }}" class="btn h-9 min-h-9 rounded-xl border border-jl-600 px-3 text-xs font-bold text-jl-700 transition hover:bg-jl-50 sm:px-4 sm:text-sm dark:border-jl-500 dark:text-jl-500 dark:hover:bg-white/10">Masuk</a>
      @else
        <a href="{{ auth()->user()->role === 'warga' ? route('warga.dashboard') : route('admin.reports') }}" class="btn h-9 min-h-9 rounded-xl border border-jl-600 px-3 text-xs font-bold text-jl-700 transition hover:bg-jl-50 sm:px-4 sm:text-sm dark:border-jl-500 dark:text-jl-500 dark:hover:bg-white/10 {{ auth()->user()->role === 'warga' ? 'hidden xl:inline-flex' : '' }}">Dashboard</a>
      @endguest
      <a href="{{ route('lapor') }}" aria-label="Lapor Cepat" class="btn btn-primary h-9 min-h-9 rounded-xl px-3 text-xs sm:px-4 sm:text-sm"><span class="site-header-full-label">Lapor Cepat</span><span class="site-header-compact-label" aria-hidden="true">Lapor</span></a>
    </div>
  </nav>
  <nav class="mobile-nav border-t border-slate-100 px-4 xl:hidden dark:border-white/10" aria-label="Navigasi cepat">
    <div class="mobile-nav-links mx-auto flex h-11 max-w-7xl items-center gap-2 overflow-x-auto">
      @foreach ([['Cara Kerja',route('home').'#cara'],['Kategori',route('home').'#kategori'],['Peta',route('peta')],['Darurat',route('darurat')],['FAQ',route('home').'#faq'],['Lacak',route('lacak')]] as [$t,$u])
        <a href="{{ $u }}" class="shrink-0 rounded-full px-3 py-1.5 text-sm font-semibold text-slate-600 transition hover:bg-jl-50 hover:text-jl-700 dark:text-slate-300 dark:hover:bg-white/10">{{ $t }}</a>
      @endforeach
      @auth
        @if (auth()->user()->role === 'warga')
          <a href="{{ route('warga.dashboard') }}" class="shrink-0 rounded-full px-3 py-1.5 text-sm font-bold text-jl-700 transition hover:bg-jl-50 dark:text-jl-500 dark:hover:bg-white/10">Dashboard</a>
        @endif
      @endauth
    </div>
  </nav>
</header>

<main class="pt-[6.75rem] xl:pt-16">@yield('content')</main>

<footer class="mt-20 bg-jl-900 text-green-100">
  <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:grid-cols-2 lg:grid-cols-3">
    <div><p class="text-xl font-extrabold text-white">JagaLampung</p><p class="mt-2 text-sm text-green-200">Platform pelaporan warga & informasi darurat.</p></div>
    <div class="text-sm"><p class="font-bold text-white">Tautan</p><ul class="mt-2 space-y-1"><li><a href="{{ route('lapor') }}">Lapor Cepat</a></li><li><a href="{{ route('lacak') }}">Lacak Laporan</a></li><li><a href="{{ route('login') }}">Masuk Warga / Petugas</a></li></ul></div>
    <div class="text-sm"><p class="font-bold text-white">Darurat</p><p class="mt-2">Nomor layanan darurat nasional: <a class="font-bold underline" href="tel:112">112</a></p></div>
  </div>
</footer>

</body>
</html>
