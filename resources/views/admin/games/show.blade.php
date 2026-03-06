@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">
    <!-- Header Navegación -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-1">
                <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
                <span class="text-slate-500">/</span>
                <a href="{{ route('admin.tournaments.show', [$league, $tournament]) }}" class="hover:text-brand-300 transition-colors">{{ $tournament->name }}</a>
            </div>
            <h1 class="text-2xl font-bold text-white">Detalle de Partido</h1>
        </div>
        <a href="{{ route('admin.games.index', [$league, $tournament]) }}" class="inline-flex items-center px-4 py-2 border border-dark-border text-sm font-medium rounded-lg text-slate-300 bg-dark-bg hover:bg-slate-800 transition-colors">
            ← Volver al Fixture
        </a>
    </div>

    <!-- Mega Tarjeta de Juego -->
    <div class="bg-dark-card rounded-2xl border border-dark-border overflow-hidden shadow-xl mb-6 relative">
        <!-- Game Status Badge Top -->
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-brand-500 via-indigo-500 to-emerald-500 opacity-50"></div>
        
        <div class="p-6 md:p-10">
            <!-- Etiqueta Estado -->
            <div class="flex justify-center mb-8">
                @if($game->status === 'finished')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 tracking-widest uppercase">
                        FINALIZADO
                    </span>
                @elseif($game->status === 'live')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-500 border border-amber-500/30 tracking-widest uppercase animate-pulse">
                        <span class="h-2 w-2 rounded-full bg-amber-500 mr-2"></span> EN VIVO
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-700 text-slate-300 border border-slate-600 tracking-widest uppercase">
                        PROGRAMADO
                    </span>
                @endif
            </div>

            <!-- Scoreboard Core -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 md:gap-4 relative">
                
                <!-- Home Team -->
                <div class="flex-1 text-center flex flex-col items-center">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Home Club</p>
                    @if($game->homeTeam->image)
                        <img src="{{ asset('storage/' . $game->homeTeam->image) }}" class="h-20 w-20 md:h-24 md:w-24 rounded-full object-cover mb-4 ring-4 ring-dark-bg shadow-lg">
                    @else
                        <div class="h-20 w-20 md:h-24 md:w-24 rounded-full bg-brand-900 border-2 border-brand-500 text-brand-300 flex items-center justify-center text-3xl font-black mb-4 shadow-lg">{{ substr($game->homeTeam->name, 0, 1) }}</div>
                    @endif
                    <h2 class="text-xl md:text-2xl font-bold text-white">{{ $game->homeTeam->name }}</h2>
                </div>

                <!-- Scores and VS -->
                <div class="flex items-center justify-center gap-6 shrink-0 z-10">
                    <div class="text-[4rem] md:text-[5rem] font-black text-white leading-none tabular-nums tracking-tighter">{{ $game->home_score ?? '0' }}</div>
                    <div class="text-slate-600 text-lg md:text-xl font-bold mt-2">VS</div>
                    <div class="text-[4rem] md:text-[5rem] font-black text-white leading-none tabular-nums tracking-tighter">{{ $game->away_score ?? '0' }}</div>
                </div>

                <!-- Away Team -->
                <div class="flex-1 text-center flex flex-col items-center">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Visitante</p>
                    @if($game->awayTeam->image)
                        <img src="{{ asset('storage/' . $game->awayTeam->image) }}" class="h-20 w-20 md:h-24 md:w-24 rounded-full object-cover mb-4 ring-4 ring-dark-bg shadow-lg">
                    @else
                        <div class="h-20 w-20 md:h-24 md:w-24 rounded-full bg-slate-800 border-2 border-slate-600 text-slate-400 flex items-center justify-center text-3xl font-black mb-4 shadow-lg">{{ substr($game->awayTeam->name, 0, 1) }}</div>
                    @endif
                    <h2 class="text-xl md:text-2xl font-bold text-white">{{ $game->awayTeam->name }}</h2>
                </div>
            </div>
        </div>

        <!-- Meta Info Bar -->
        <div class="bg-dark-bg/60 border-t border-dark-border p-4">
            <div class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-sm">
                <div class="flex items-center text-slate-300">
                    <svg class="mr-1.5 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Fecha: <span class="text-white ml-1 font-medium">{{ $game->game_date ? \Carbon\Carbon::parse($game->game_date)->format('d M Y') : 'Por definir' }}</span>
                </div>
                <div class="flex items-center text-slate-300">
                    <svg class="mr-1.5 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Grupo: <span class="text-white ml-1 font-medium">{{ $game->group->name ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center text-slate-300">
                    <svg class="mr-1.5 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Fase: <span class="text-white ml-1 font-medium">{{ ucfirst($game->stage->value) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Inferiores -->
    <div class="flex justify-center gap-4">
        <a href="{{ route('admin.games.edit', [$league, $tournament, $game]) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-dark-bg bg-amber-400 hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-dark-card focus:ring-amber-500 transition-colors shadow-sm">
            Editar Score y Ajustes
        </a>
    </div>
</div>

@endsection