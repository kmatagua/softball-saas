@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Torneo</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                {{ $tournament->name }}
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $tournament->is_active ? 'bg-emerald-400/10 text-emerald-400 ring-emerald-400/20' : 'bg-slate-400/10 text-slate-400 ring-slate-400/20' }}">
                    {{ $tournament->is_active ? 'En Curso' : 'Finalizado' }}
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-400">Panel de control de la categoría. Administra equipos, grupos y el calendario.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.leagues.show', $league) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors shadow-sm">
                Volver a Torneos
            </a>
            <form method="POST" action="{{ route('admin.tournaments.generatePlayoffs', [$league, $tournament]) }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-bold rounded-lg text-white bg-red-600 hover:bg-red-500 transition-colors shadow-[0_0_15px_rgba(220,38,38,0.5)] hover:shadow-[0_0_20px_rgba(220,38,38,0.7)]" onclick="return confirm('¿Generar rondas de Playoff automáticamente con los clasificados actuales?')">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Generar Playoffs
                </button>
            </form>
        </div>
    </div>

    <!-- ACTION BAR (Barra de Tareas Principal) -->
    <div class="mb-8 bg-dark-card border border-dark-border rounded-2xl shadow-lg p-2 flex flex-col sm:flex-row gap-2 relative overflow-hidden">
        <!-- Decoración Sutil -->
        <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-brand-500 via-emerald-500 to-brand-500 opacity-50"></div>
        
        <a href="{{ route('admin.groups.create', $league) }}" class="flex-1 flex items-center justify-center gap-2 py-4 px-4 rounded-xl bg-dark-bg hover:bg-dark-hover border border-transparent hover:border-dark-border transition-all group">
            <div class="h-10 w-10 rounded-full bg-indigo-500/10 text-indigo-400 flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div class="text-left">
                <div class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">Crear Grupo</div>
                <div class="text-xs text-slate-400">Subdividir torneo</div>
            </div>
        </a>

        <div class="hidden sm:block w-px bg-dark-border my-2"></div>

        <a href="{{ route('admin.teams.create', [$league, $tournament]) }}" class="flex-1 flex items-center justify-center gap-2 py-4 px-4 rounded-xl bg-dark-bg hover:bg-dark-hover border border-transparent hover:border-dark-border transition-all group">
            <div class="h-10 w-10 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div class="text-left">
                <div class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Inscribir Equipo</div>
                <div class="text-xs text-slate-400">Agregar participantes</div>
            </div>
        </a>

        <div class="hidden sm:block w-px bg-dark-border my-2"></div>

        <a href="{{ route('admin.games.create', [$league, $tournament]) }}" class="flex-1 flex items-center justify-center gap-2 py-4 px-4 rounded-xl bg-dark-bg hover:bg-dark-hover border border-transparent hover:border-dark-border transition-all group">
            <div class="h-10 w-10 rounded-full bg-brand-500/10 text-brand-400 flex items-center justify-center group-hover:scale-110 group-hover:bg-brand-500 group-hover:text-white transition-all">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div class="text-left">
                <div class="text-sm font-bold text-white group-hover:text-brand-400 transition-colors">Programar Juego</div>
                <div class="text-xs text-slate-400">Fijar fecha de partido</div>
            </div>
        </a>
    </div>

    <!-- Contenido Principal -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Vista General del Torneo y Accesos Directos -->
        <div class="xl:col-span-1 space-y-6">
            <div class="bg-dark-card rounded-2xl border border-dark-border p-6 shadow-md relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                    <svg class="h-32 w-32 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                
                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Resumen del Torneo
                </h3>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-dark-bg border border-dark-border rounded-xl p-4 text-center">
                        <span class="block text-2xl font-black text-white">{{ $tournament->groups_count }}</span>
                        <span class="block text-xs text-slate-400 uppercase tracking-wider mt-1">Grupos</span>
                    </div>
                    <div class="bg-dark-bg border border-dark-border rounded-xl p-4 text-center">
                        @php $totalTeams = $tournament->groups->sum(function($group) { return $group->teams->count(); }); @endphp
                        <span class="block text-2xl font-black text-brand-400">{{ $totalTeams }}</span>
                        <span class="block text-xs text-slate-400 uppercase tracking-wider mt-1">Equipos</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-dark-bg rounded-lg border border-dark-border text-sm">
                        <span class="text-slate-400">Pasan a Playoffs</span>
                        <span class="font-bold text-white">{{ $tournament->qualifies_per_group }} por grupo</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-dark-bg rounded-lg border border-dark-border text-sm">
                        <span class="text-slate-400">Pts por Ganar</span>
                        <span class="font-bold text-emerald-400">+{{ $tournament->points_per_win }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-dark-bg rounded-lg border border-dark-border text-sm">
                        <span class="text-slate-400">Pts por Empatar</span>
                        <span class="font-bold text-amber-400">+{{ $tournament->points_per_draw }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-dark-bg rounded-lg border border-dark-border text-sm">
                        <span class="text-slate-400">Pts por Perder</span>
                        <span class="font-bold text-red-400">{{ $tournament->points_per_loss }}</span>
                    </div>
                </div>

                <div class="mt-8 flex flex-col gap-3 relative z-10">
                    <a href="{{ route('admin.tournaments.standings', [$league, $tournament]) }}" class="w-full flex items-center justify-between px-5 py-3 bg-gradient-to-r from-amber-600 to-amber-500 text-sm font-bold rounded-xl text-white hover:from-amber-500 hover:to-amber-400 transition-all shadow-lg shadow-amber-500/20 group">
                        Ver Tabla de Posiciones
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('admin.games.index', [$league, $tournament]) }}" class="w-full flex items-center justify-between px-5 py-3 bg-dark-bg border border-dark-border text-sm font-bold rounded-xl text-white hover:bg-dark-hover transition-all group">
                        Calendario y Resultados
                        <svg class="w-5 h-5 text-slate-400 group-hover:translate-x-1 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Grupos y Sub-Divisiones -->
        <div class="xl:col-span-2">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Grupos del Torneo
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">Los equipos se dividen en los siguientes grupos para la fase regular.</p>
                </div>
            </div>

            @if($tournament->groups->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($tournament->groups as $group)
                        <div class="bg-dark-card rounded-2xl border border-dark-border shadow-md overflow-hidden flex flex-col group/card hover:border-brand-500/30 transition-colors">
                            <div class="bg-dark-bg px-5 py-4 border-b border-dark-border flex justify-between items-center relative overflow-hidden">
                                <!-- Subtle bg accent -->
                                <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/5 rounded-full blur-2xl -mr-10 -mt-10"></div>
                                
                                <h3 class="text-lg font-bold text-white relative z-10">{{ $group->name }}</h3>
                                <span class="relative z-10 inline-flex items-center rounded-full bg-slate-800 border border-slate-700 px-3 py-1 text-xs font-bold text-brand-400">
                                    {{ $group->teams->count() }} Equipos
                                </span>
                            </div>
                            
                            <div class="p-5 flex-1 bg-dark-card">
                                @if($group->teams->count())
                                    <ul class="space-y-3">
                                        @foreach($group->teams as $team)
                                            <li class="flex items-center gap-3 p-2 rounded-lg hover:bg-dark-bg transition-colors border border-transparent hover:border-dark-border">
                                                @if($team->image)
                                                    <img src="{{ asset('storage/' . $team->image) }}" class="h-8 w-8 rounded-full object-cover ring-2 ring-dark-bg">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-slate-600 to-slate-800 ring-2 ring-dark-bg flex items-center justify-center text-xs text-white font-bold shadow-sm">
                                                        {{ strtoupper(substr($team->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                                <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="flex-1 text-sm font-medium text-slate-200 hover:text-brand-400 transition-colors truncate">
                                                    {{ $team->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="h-full flex flex-col items-center justify-center py-6 text-center">
                                        <svg class="h-10 w-10 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <p class="text-sm text-slate-400">Grupo vacío</p>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('admin.teams.create', ['league' => $league, 'tournament' => $tournament, 'group' => $group->id]) }}" class="bg-dark-bg/50 px-5 py-3 border-t border-dark-border mt-auto flex items-center justify-center text-sm font-medium text-brand-500 hover:text-brand-400 hover:bg-dark-bg transition-colors">
                                Inscribir Equipo 
                                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="border-2 border-dashed border-dark-border rounded-xl p-16 text-center bg-dark-card/50">
                    <svg class="mx-auto h-12 w-12 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-white">No hay grupos creados</h3>
                    <p class="mt-2 text-base text-slate-400 max-w-md mx-auto">Para inscribir equipos, primero necesitas dividir el torneo en grupos o áreas.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection