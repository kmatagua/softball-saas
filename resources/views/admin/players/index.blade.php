@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
            <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
            <span class="text-slate-500">/</span>
            <span class="text-slate-300">Jugadores</span>
        </div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Directorio de Jugadores</h1>
        <p class="mt-1 text-sm text-slate-400">Listado general de todos los jugadores inscritos en los equipos de esta liga.</p>
    </div>
</div>

<div class="bg-dark-card rounded-xl border border-dark-border shadow-sm overflow-hidden">
    @if($players->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/50 border-b border-dark-border">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium">Jugador</th>
                        <th scope="col" class="px-6 py-4 font-medium">Equipo</th>
                        <th scope="col" class="px-6 py-4 font-medium">Torneo</th>
                        <th scope="col" class="px-6 py-4 text-center font-medium"># Jersey</th>
                        <th scope="col" class="px-6 py-4 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border bg-dark-bg/50">
                    @foreach($players as $player)
                        <tr class="hover:bg-dark-hover/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    @if($player->image)
                                        <img src="{{ asset('storage/' . $player->image) }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-brand-500/30">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-sm text-brand-300 font-bold ring-2 ring-slate-700">
                                            {{ substr($player->first_name, 0, 1) }}{{ substr($player->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-white">{{ $player->first_name }} {{ $player->last_name }}</div>
                                        <div class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                            <svg class="h-3 w-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                            {{ $player->dni ?? 'Sin DNI' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                @if($player->team)
                                    <a href="{{ route('admin.teams.show', [$league, $player->team]) }}" class="hover:text-brand-400 transition-colors">{{ $player->team->name }}</a>
                                @else
                                    <span class="text-slate-500">Sin Equipo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs">
                                {{ $player->team->tournament->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-dark-card border border-dark-border text-slate-300 font-bold">
                                    {{ $player->jersey_number ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.players.show', [$league, $player->team, $player]) }}" class="inline-flex items-center text-brand-400 hover:text-brand-300 font-medium">
                                    Estadísticas →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-dark-border bg-dark-bg">
            {{ $players->links() }}
        </div>
    @else
        <div class="text-center py-12 px-4">
            <svg class="mx-auto h-12 w-12 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <h3 class="text-lg font-medium text-white mb-1">No hay jugadores</h3>
            <p class="text-slate-400 text-sm">Los jugadores se registran desde la ficha de cada equipo.</p>
        </div>
    @endif
</div>
@endsection
