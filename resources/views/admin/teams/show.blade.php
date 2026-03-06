@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">
    <!-- Team Profile Header -->
    <div class="bg-dark-card rounded-2xl border border-dark-border overflow-hidden shadow-sm mb-8 relative">
        <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-r from-brand-600 to-indigo-800"></div>
        <div class="relative px-6 pb-6 pt-16 flex flex-col sm:flex-row justify-between items-end gap-6">
            <div class="flex items-end gap-5">
                <div class="h-32 w-32 rounded-2xl border-4 border-dark-card bg-dark-bg shadow-md overflow-hidden bg-white/5 flex items-center justify-center relative z-10">
                    @if($team->image)
                        <img src="{{ asset('storage/' . $team->image) }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-4xl font-extrabold text-slate-600">{{ substr($team->name, 0, 1) }}</span>
                    @endif
                </div>
                <div class="mb-1">
                    <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-0.5 text-xs font-semibold text-brand-400 mb-2">
                        EQUIPO ASOCIADO
                    </span>
                    <h1 class="text-3xl font-bold tracking-tight text-white">{{ $team->name }}</h1>
                    <p class="text-sm text-slate-400 mt-1">Liga: {{ $league->name }}</p>
                </div>
            </div>
            
            <div class="flex gap-3 mb-2 w-full sm:w-auto">
                <a href="{{ route('admin.teams.edit', [$league, $team]) }}" class="flex-1 justify-center sm:flex-none inline-flex items-center px-4 py-2 border border-slate-600 shadow-sm text-sm font-medium rounded-lg text-slate-300 bg-dark-bg hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-dark-card focus:ring-slate-500 transition-colors">
                    Editar Equipo
                </a>
                <a href="{{ route('admin.leagues.show', $league) }}" class="flex-1 justify-center sm:flex-none inline-flex items-center px-4 py-2 border border-brand-600 text-sm font-medium rounded-lg text-white bg-brand-600 hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-dark-card focus:ring-brand-500 transition-colors shadow-sm">
                    Ir a Liga
                </a>
            </div>
        </div>
    </div>

    <!-- Players Roster -->
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">Roster de Jugadores</h2>
        <a href="{{ route('admin.players.create', [$league, $team]) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-500 transition-colors shadow-sm">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Inscribir Jugador
        </a>
    </div>

    @if($team->players->count())
        <div class="bg-dark-card border border-dark-border rounded-xl shadow-sm overflow-hidden">
            <ul role="list" class="divide-y divide-dark-border/50">
                @foreach($team->players as $player)
                    <li class="p-4 hover:bg-dark-hover transition-colors flex items-center justify-between gap-x-6">
                        <div class="flex min-w-0 gap-x-4 items-center">
                            @if($player->image)
                                <img class="h-12 w-12 flex-none rounded-full bg-slate-800 object-cover ring-2 ring-dark-border" src="{{ asset('storage/' . $player->image) }}" alt="">
                            @else
                                <div class="h-12 w-12 flex-none rounded-full bg-slate-800 flex items-center justify-center ring-2 ring-dark-border">
                                    <svg class="h-6 w-6 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                            @endif
                            <div class="min-w-0 flex-auto">
                                <p class="text-sm font-semibold leading-6 text-white">
                                    <span class="text-brand-400 mr-1">#{{ $player->jersey_number ?? '-' }}</span>
                                    <a href="{{ route('admin.players.show', [$league, $team, $player]) }}" class="hover:underline">
                                        {{ $player->first_name }} {{ $player->last_name }}
                                    </a>
                                </p>
                                @if($player->dni)
                                    <p class="mt-1 truncate text-xs leading-5 text-slate-400">DNI: {{ $player->dni }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-x-2">
                            <a href="{{ route('admin.players.edit', [$league, $team, $player]) }}" class="rounded bg-dark-bg border border-dark-border px-2 py-1 text-xs font-semibold text-slate-300 hover:bg-slate-700">Editar</a>
                            <form action="{{ route('admin.players.destroy', [$league, $team, $player]) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Remover este jugador del equipo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded bg-red-500/10 border border-red-500/20 px-2 py-1 text-xs font-semibold text-red-400 hover:bg-red-500/20">Quitar</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-12 bg-dark-card rounded-xl border border-dark-border border-dashed">
            <svg class="mx-auto h-12 w-12 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-white">No hay jugadores inscritos</h3>
            <p class="mt-1 text-sm text-slate-400">Construye tu Lineup añadiendo jugadores a la plantilla.</p>
        </div>
    @endif
</div>

@endsection