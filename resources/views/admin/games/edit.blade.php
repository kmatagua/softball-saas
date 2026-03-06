@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.tournaments.show', [$league, $tournament]) }}" class="hover:text-brand-300 transition-colors">{{ $tournament->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Juego Definitivo</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Actualizar Box Score</h1>
            <p class="mt-1 text-sm text-slate-400">Edita el resultado o cambia el status de {{ $game->homeTeam->name }} vs {{ $game->awayTeam->name }}.</p>
        </div>
        <a href="{{ route('admin.games.index', [$league, $tournament]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
            Cancelar / Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-500/10 border border-red-500/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-400">Verifica los errores</h3>
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

    <div class="bg-dark-card rounded-xl border border-dark-border shadow-sm overflow-hidden p-6 md:p-8">
        <form method="POST" action="{{ route('admin.games.update', [$league, $tournament, $game]) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative items-center">
                <!-- Vínculo visual -->
                <div class="hidden md:block absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-slate-600 font-bold text-xl uppercase tracking-widest z-0">
                    VS
                </div>

                <!-- Home Score -->
                <div class="bg-dark-bg/50 p-6 rounded-xl border border-dark-border relative z-10 text-center flex flex-col items-center">
                    <p class="text-sm font-medium text-brand-400 mb-2 uppercase tracking-widest">Home Club</p>
                    <h2 class="text-xl font-bold text-white mb-4">{{ $game->homeTeam->name }}</h2>
                    <div class="w-full">
                        <label class="sr-only">Marcador Home</label>
                        <input type="number" name="home_score" value="{{ old('home_score', $game->home_score) }}" class="block w-full text-center rounded-lg border-0 py-3 text-2xl font-black bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500" placeholder="0">
                    </div>
                </div>

                <!-- Away Score -->
                <div class="bg-dark-bg/50 p-6 rounded-xl border border-dark-border relative z-10 text-center flex flex-col items-center">
                    <p class="text-sm font-medium text-amber-400 mb-2 uppercase tracking-widest">Visitante</p>
                    <h2 class="text-xl font-bold text-white mb-4">{{ $game->awayTeam->name }}</h2>
                    <div class="w-full">
                        <label class="sr-only">Marcador Away</label>
                        <input type="number" name="away_score" value="{{ old('away_score', $game->away_score) }}" class="block w-full text-center rounded-lg border-0 py-3 text-2xl font-black bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500" placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="pt-6 border-t border-dark-border">
                <label class="block text-sm font-medium text-white mb-3">Estado del Partido</label>
                <select name="status" class="block w-full rounded-lg border-0 py-3 pl-3 pr-10 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6">
                    <option value="scheduled" {{ $game->status == 'scheduled' ? 'selected' : '' }}>Programado</option>
                    <option value="live" {{ $game->status == 'live' ? 'selected' : '' }}>En Vivo / En Progreso</option>
                    <option value="finished" {{ $game->status == 'finished' ? 'selected' : '' }}>Finalizado</option>
                </select>
                <p class="mt-2 text-xs text-slate-400">Si lo configuras como Finalizado, el resultado impactará en la Tabla de Posiciones general según las reglas del torneo.</p>
            </div>

            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-dark-border pt-6">
                <button type="submit" class="inline-flex items-center rounded-lg bg-brand-600 px-8 py-3 text-sm font-bold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 transition-all">
                    Guardar / Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection