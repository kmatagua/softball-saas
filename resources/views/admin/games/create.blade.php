@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">
    
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
                <span class="text-slate-500">/</span>
                <a href="{{ route('admin.games.index', [$league, $tournament]) }}" class="hover:text-brand-300 transition-colors">{{ $tournament->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Programar Juego</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Programar Nuevo Juego</h1>
            <p class="mt-1 text-sm text-slate-400">Selecciona los equipos y configura los detalles iniciales del encuentro.</p>
        </div>
        
        <a href="{{ route('admin.games.index', [$league, $tournament]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
            Cancelar
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-500/10 border border-red-500/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-400">Se encontraron errores en tu formulario</h3>
                    <div class="mt-2 text-sm text-red-300">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-dark-card rounded-xl border border-dark-border shadow-sm overflow-hidden">
        
        <!-- Paso 1: Selección de Grupo (Hace reload automático) -->
        <div class="p-6 border-b border-dark-border bg-slate-800/30">
            <form method="GET" action="{{ route('admin.games.create', [$league, $tournament]) }}">
                <label class="block text-sm font-medium text-white mb-2">1. Selecciona el Grupo / Categoría</label>
                <div class="flex gap-4 items-center">
                    <select name="group_id" class="block w-full rounded-lg border-0 py-2.5 pl-3 pr-10 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" onchange="this.form.submit()">
                        <option value="">-- Elige un grupo primero --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ $selectedGroupId == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="mt-2 text-xs text-slate-400">Los equipos disponibles dependerán del grupo seleccionado.</p>
            </form>
        </div>

        <!-- Paso 2: Detalles del Encuentro -->
        <div class="p-6">
            <form method="POST" action="{{ route('admin.games.store', [$league, $tournament]) }}" class="space-y-6">
                @csrf
                <input type="hidden" name="group_id" value="{{ $selectedGroupId }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Fase del Torneo</label>
                        <select name="stage" class="block w-full rounded-lg border-0 py-2.5 pl-3 pr-10 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required>
                            <option value="regular">Temporada Regular</option>
                            <option value="quarterfinal">Cuartos de Final</option>
                            <option value="semifinal">Semifinal</option>
                            <option value="final">Final</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Fecha y Hora (Opcional)</label>
                        <input type="datetime-local" name="game_date" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-white mb-2">Locación / Estadio (Opcional)</label>
                    <input type="text" name="location" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" placeholder="Ej: Estadio Central">
                </div>

                @if($selectedGroupId)
                    <div class="mt-8 pt-6 border-t border-dark-border">
                        <h4 class="text-sm font-medium text-white mb-4">2. Selecciona los Equipos Adversarios</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end relative">
                            
                            <!-- Separador "VS" visual en pantallas medianas -->
                            <div class="hidden md:flex absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-10 h-10 bg-dark-bg rounded-full border border-dark-border items-center justify-center z-10">
                                <span class="text-xs font-bold text-slate-500">VS</span>
                            </div>

                            <div class="bg-dark-bg rounded-lg p-5 border border-dark-border relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-2 h-full bg-slate-600"></div>
                                <label class="block text-xs font-bold tracking-wider text-slate-400 uppercase mb-3">Equipo Visitante (Batea 1ro)</label>
                                <select name="away_team_id" class="block w-full rounded border-0 py-2 pl-3 pr-10 bg-dark-card text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bg-dark-bg rounded-lg p-5 border border-dark-border relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-2 h-full bg-brand-600"></div>
                                <label class="block text-xs font-bold tracking-wider text-slate-400 uppercase mb-3 text-right">Equipo Local (Defiende 1ro)</label>
                                <select name="home_team_id" class="block w-full rounded border-0 py-2 pl-3 pr-10 bg-dark-card text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required dir="rtl">
                                    <option value="" dir="ltr">-- Seleccionar --</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" dir="ltr">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-8 pt-6 border-t border-dark-border text-center py-8 bg-dark-bg rounded-lg border-dashed">
                        <svg class="mx-auto h-8 w-8 text-slate-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-slate-400">
                            Por favor selecciona un grupo en el Paso 1 para ver los equipos.
                        </p>
                    </div>
                @endif

                <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-dark-border pt-6">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 transition-all select-none" {{ !$selectedGroupId ? 'disabled' : '' }}>
                        <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Programar Juego Ahora
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection