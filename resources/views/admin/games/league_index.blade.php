@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
            <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
            <span class="text-slate-500">/</span>
            <span class="text-slate-300">Juegos</span>
        </div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Calendario Global de Juegos</h1>
        <p class="mt-1 text-sm text-slate-400">Todos los partidos programados en la liga, sin importar la categoría.</p>
    </div>
</div>

<div class="bg-dark-card rounded-xl border border-dark-border shadow-sm overflow-hidden">
    @if($games->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/50 border-b border-dark-border">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium">Fecha</th>
                        <th scope="col" class="px-6 py-4 font-medium">Torneo (Fase)</th>
                        <th scope="col" class="px-6 py-4 font-medium">Encuentro (Visitante @ Local)</th>
                        <th scope="col" class="px-6 py-4 text-center font-medium">Estado</th>
                        <th scope="col" class="px-6 py-4 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border bg-dark-bg/50">
                    @foreach($games as $game)
                        <tr class="hover:bg-dark-hover/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-slate-300">
                                @if($game->game_date)
                                    {{ \Carbon\Carbon::parse($game->game_date)->format('d M, Y') }}
                                @else
                                    <span class="text-slate-500 italic">Por definir</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                <div class="font-medium">{{ $game->tournament->name ?? '-' }}</div>
                                <div class="text-xs py-0.5 px-2 mt-1 inline-block rounded-md bg-dark-card border border-dark-border text-slate-400 uppercase tracking-wider">
                                    {{ $game->stage }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="text-right w-1/3 flex items-center justify-end gap-2">
                                        <span class="truncate font-medium {{ $game->away_score > $game->home_score ? 'text-white' : 'text-slate-400' }}">{{ $game->awayTeam->name }}</span>
                                        <span class="text-lg font-bold text-slate-300">{{ $game->away_score ?? '-' }}</span>
                                    </div>
                                    <div class="text-xs font-bold text-brand-500 bg-brand-500/10 px-2 py-1 rounded">VS</div>
                                    <div class="w-1/3 flex items-center justify-start gap-2">
                                        <span class="text-lg font-bold text-slate-300">{{ $game->home_score ?? '-' }}</span>
                                        <span class="truncate font-medium {{ $game->home_score > $game->away_score ? 'text-white' : 'text-slate-400' }}">{{ $game->homeTeam->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($game->status === 'scheduled')
                                    <span class="inline-flex items-center rounded-md bg-amber-500/10 px-2 py-1 text-xs font-medium text-amber-400 ring-1 ring-inset ring-amber-500/20">Programado</span>
                                @elseif($game->status === 'in_progress')
                                    <span class="inline-flex items-center rounded-md bg-emerald-500/10 px-2 py-1 text-xs font-medium text-emerald-400 ring-1 ring-inset ring-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                        En Vivo
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-slate-500/10 px-2 py-1 text-xs font-medium text-slate-400 ring-1 ring-inset ring-slate-500/20">Finalizado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.games.show', [$league, $game->tournament, $game]) }}" class="inline-flex items-center text-brand-400 hover:text-brand-300 font-medium">
                                    Gestionar →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 px-4">
            <svg class="mx-auto h-12 w-12 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-lg font-medium text-white mb-1">El calendario está vacío</h3>
            <p class="text-slate-400 text-sm">Los juegos se programan entrando a la gestión detallada de cada torneo.</p>
        </div>
    @endif
</div>
@endsection
