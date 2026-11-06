<nav class="mb-7 flex flex-wrap gap-2 text-sm font-semibold" aria-label="Menu admin">
  @foreach ([['Antrean','admin.reports'],['Statistik','admin.stats'],['Darurat','admin.darurat']] as [$t,$r])
    <a href="{{ route($r) }}" @if (request()->routeIs($r)) aria-current="page" @endif class="rounded-full border border-slate-200 px-4 py-2 transition hover:border-jl-200 hover:bg-jl-50 dark:border-white/10 dark:hover:bg-white/10 {{ request()->routeIs($r) ? 'border-jl-600 bg-jl-600 text-white hover:bg-jl-600 dark:border-jl-600' : 'bg-white shadow-sm dark:bg-white/5' }}">{{ $t }}</a>
  @endforeach
  @if (auth()->user()->role === 'super_admin')
    <a href="{{ route('admin.users') }}" @if (request()->routeIs('admin.users')) aria-current="page" @endif class="rounded-full border border-slate-200 px-4 py-2 shadow-sm transition hover:border-jl-200 hover:bg-jl-50 dark:border-white/10 dark:bg-white/5 dark:hover:bg-white/10 {{ request()->routeIs('admin.users') ? 'border-jl-600 bg-jl-600 text-white hover:bg-jl-600 dark:border-jl-600' : 'bg-white' }}">Pengguna</a>
  @endif
</nav>
