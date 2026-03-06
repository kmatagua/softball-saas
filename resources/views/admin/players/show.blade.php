@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">
    <!-- Header de Jugador -->
    <div class="bg-dark-card rounded-2xl border border-dark-border p-6 shadow-sm mb-8 flex flex-col md:flex-row justify-between items-center md:items-start gap-6 relative overflow-hidden">
        <div class="absolute inset-y-0 right-0 w-1/4 bg-gradient-to-l from-brand-900/40 to-transparent pointer-events-none"></div>
        
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left z-10">
            @if($player->image)
                <img src="{{ asset('storage/' . $player->image) }}" class="h-28 w-28 rounded-full border-4 border-dark-card object-cover shadow-lg ring-2 ring-brand-500/50">
            @else
                <div class="h-28 w-28 rounded-full border-4 border-dark-card bg-slate-800 shadow-lg ring-2 ring-slate-600 flex items-center justify-center">
                    <svg class="h-14 w-14 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            @endif

            <div class="mt-2">
                <h1 class="text-3xl font-extrabold tracking-tight text-white mb-2">
                    <span class="text-brand-400 font-black mr-2">#{{ $player->jersey_number ?? '-' }}</span>
                    {{ $player->first_name }} {{ $player->last_name }}
                </h1>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                    <span class="inline-flex items-center rounded-md bg-slate-800 px-2.5 py-1 text-sm font-medium text-slate-300">
                        Equipo: <span class="text-white ml-1">{{ $team->name }}</span>
                    </span>
                    @if($player->dni)
                        <span class="inline-flex items-center rounded-md bg-slate-800/50 border border-slate-700 px-2.5 py-1 text-xs font-medium text-slate-400">
                            ID: {{ $player->dni }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-slate-300 bg-dark-bg border border-dark-border hover:bg-slate-800 transition-colors z-10">
            Ver Todo el Equipo
        </a>
    </div>

    <!-- Estadísticas Stats Grid -->
    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
        <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        Estadísticas de Temporada
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-dark-card border border-dark-border rounded-xl p-4 text-center">
            <p class="text-sm font-medium text-slate-400">Juegos (G)</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $gamesPlayed }}</p>
        </div>
        
        <div class="bg-dark-card border border-dark-border rounded-xl p-4 text-center">
            <p class="text-sm font-medium text-slate-400">Turnos (AB)</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $atBats }}</p>
        </div>
        
        <div class="bg-dark-card border border-brand-500/20 rounded-xl p-4 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-brand-500/5"></div>
            <p class="relative z-10 text-sm font-medium text-brand-400">Hits (H)</p>
            <p class="relative z-10 mt-2 text-3xl font-bold text-brand-100">{{ $hits }}</p>
        </div>
        
        <div class="bg-dark-card border border-emerald-500/20 rounded-xl p-4 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-emerald-500/5"></div>
            <p class="relative z-10 text-sm font-medium text-emerald-400">Carreras (R)</p>
            <p class="relative z-10 mt-2 text-3xl font-bold text-emerald-100">{{ $runs }}</p>
        </div>
        
        <div class="bg-dark-card border border-amber-500/20 rounded-xl p-4 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-amber-500/5"></div>
            <p class="relative z-10 text-sm font-medium text-amber-500">Impulsadas (RBI)</p>
            <p class="relative z-10 mt-2 text-3xl font-bold text-amber-100">{{ $rbi }}</p>
        </div>
        
        <div class="bg-dark-card border border-violet-500/30 rounded-xl p-4 text-center relative shadow-[0_0_15px_rgba(139,92,246,0.1)]">
            <p class="text-sm font-bold text-violet-400 uppercase tracking-widest text-xs">Averaje</p>
            <p class="mt-2 text-3xl font-black text-white">{{ $average }}</p>
        </div>
    </div>
</div>

@endsection