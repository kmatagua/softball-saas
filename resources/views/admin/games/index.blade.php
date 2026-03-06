@extends('layouts.app')

@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
            @if(isset($tournament))
                <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
                <span>/</span>
                <span class="text-slate-400">{{ $tournament->name }}</span>
            @else
                <span class="text-slate-400">{{ $league->name }}</span>
            @endif
        </div>
        <h1 class="text-3xl font-bold tracking-tight text-white mb-1">Calendario de Juegos</h1>
        <p class="text-sm text-slate-400">Administra los próximos partidos o revisa el historial.</p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.leagues.show', $league) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
            <svg class="mr-2 -ml-1 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
        @isset($tournament)
            <a href="{{ route('admin.games.create', [$league, $tournament]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-brand-600 hover:bg-brand-500 transition-colors shadow-sm">
                <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Programar Juego
            </a>
        @endisset
    </div>
</div>

@if($games->count())
    <div class="bg-dark-card rounded-xl border border-dark-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left bg-dark-card">
                <thead>
                    <tr class="border-b border-dark-border bg-slate-800/50 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th scope="col" class="px-6 py-4">Fecha</th>
                        <th scope="col" class="px-6 py-4">Equipos</th>
                        <th scope="col" class="px-6 py-4 text-center">Marcador</th>
                        <th scope="col" class="px-6 py-4 text-center">Estado</th>
                        <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @foreach($games as $game)
                        <tr class="hover:bg-dark-hover/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                <span class="font-medium text-white">{{ \Carbon\Carbon::parse($game->game_date)->format('d M') }}</span>
                                <span class="block text-xs text-slate-500">{{ \Carbon\Carbon::parse($game->game_date)->format('h:i A') }}</span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-5 h-5 rounded bg-slate-800 ring-1 ring-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-400">V</div>
                                            <span class="text-sm font-medium text-slate-300 @if($game->status === 'finished' && $game->away_score > $game->home_score) text-white font-bold @endif">{{ $game->awayTeam->name }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-5 h-5 rounded bg-slate-800 ring-1 ring-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-400">L</div>
                                            <span class="text-sm font-medium text-slate-300 @if($game->status === 'finished' && $game->home_score > $game->away_score) text-white font-bold @endif">{{ $game->homeTeam->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($game->status === 'finished' || $game->status === 'live')
                                    <div class="inline-flex flex-col items-center bg-dark-bg border border-dark-border rounded px-3 py-1.5 shadow-sm">
                                        <span class="text-xs font-bold @if($game->status === 'live') text-yellow-400 @else text-white @endif">{{ $game->away_score }}</span>
                                        <span class="text-[10px] text-slate-600 block h-[1px] w-full bg-dark-border my-0.5"></span>
                                        <span class="text-xs font-bold @if($game->status === 'live') text-yellow-400 @else text-white @endif">{{ $game->home_score }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-slate-500">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center gap-1.5 align-middle justify-center h-full"> 
                                    @if($game->status === 'finished')
                                        <span class="inline-flex items-center rounded-md bg-green-500/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-500/20">Finalizado</span>
                                    @elseif($game->status === 'live')
                                        <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-400 ring-1 ring-inset ring-yellow-400/20">En Vivo</span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-slate-400/10 px-2 py-1 text-xs font-medium text-slate-400 ring-1 ring-inset ring-slate-400/20">Programado</span>
                                    @endif
                                    <span class="text-[10px] font-medium text-slate-500 uppercase tracking-widest">{{ $game->stage->value }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.games.show', [$league, $tournament, $game]) }}" class="inline-flex items-center px-2 py-1.5 text-xs font-medium rounded text-brand-400 bg-brand-500/10 hover:bg-brand-500/20 transition-colors">
                                        Anotación
                                    </a>
                                    <a href="{{ route('admin.games.edit', [$league, $tournament, $game]) }}" class="p-1.5 text-slate-400 hover:text-white transition-colors" title="Editar config">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.games.destroy', [$league, $tournament, $game]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar el juego? Se perderá todo el historial del mismo.')" class="p-1.5 text-red-500 hover:text-red-400 hover:bg-red-500/10 rounded transition-colors" title="Eliminar">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="border-2 border-dashed border-dark-border rounded-xl p-12 text-center bg-dark-card/50 mt-6">
        <svg class="mx-auto h-12 w-12 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="mt-4 text-sm font-semibold text-white">No hay juegos programados</h3>
        <p class="mt-1 text-sm text-slate-400">El calendario para esta etapa está vacío por el momento.</p>
        @isset($tournament)
            <div class="mt-6">
                <a href="{{ route('admin.games.create', [$league, $tournament]) }}" class="inline-flex items-center rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-500">
                    Programar Primer Juego
                </a>
            </div>
        @endisset
    </div>
@endif

@endsection