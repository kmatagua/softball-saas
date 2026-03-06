@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.tournaments.show', [$league, $tournament]) }}" class="hover:text-brand-300 transition-colors">{{ $tournament->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Standings</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <svg class="h-8 w-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Tabla de Posiciones
            </h1>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.leagues.show', $league) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
                Liga
            </a>
            <a href="{{ route('admin.games.index', [$league, $tournament]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-brand-600 hover:bg-brand-500 transition-colors shadow-sm">
                Ver Juegos Recientes →
            </a>
        </div>
    </div>

    @foreach($standings as $groupName => $table)
        <div class="mb-10 last:mb-0">
            <div class="flex items-center gap-3 mb-4 pl-2">
                <h2 class="text-xl font-bold text-white uppercase tracking-wider">{{ $groupName }}</h2>
                <div class="flex-grow h-px bg-dark-border"></div>
            </div>

            <div class="bg-dark-card rounded-xl border border-dark-border overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-dark-border">
                        <thead class="bg-dark-bg text-left">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-xs font-bold text-slate-300 uppercase tracking-widest sm:pl-6 w-16 text-center">Pos</th>
                                <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-300 uppercase tracking-wider">Equipo</th>
                                <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-300 uppercase tracking-wider text-center" title="Partidos Jugados">PJ</th>
                                <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-brand-400 uppercase tracking-wider text-center" title="Partidos Ganados">PG</th>
                                <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-300 uppercase tracking-wider text-center" title="Partidos Empatados">PE</th>
                                <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-red-400 uppercase tracking-wider text-center" title="Partidos Perdidos">PP</th>
                                <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center hidden sm:table-cell" title="Carreras a Favor">CF</th>
                                <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center hidden sm:table-cell" title="Carreras en Contra">CC</th>
                                <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center" title="Diferencia">DIF</th>
                                <th scope="col" class="px-3 py-3.5 text-sm font-black text-amber-400 uppercase tracking-widest text-center bg-dark-bg/50 border-l border-dark-border w-24">PTS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-border bg-dark-card font-medium">
                            @foreach($table as $row)
                                @php
                                    $rowStyles = 'hover:bg-dark-hover transition-colors';
                                    $rankStyles = 'bg-slate-800 text-slate-300';
                                    $teamStyles = 'text-white';
                                    
                                    if ($row['status'] === 'qualified') {
                                        $rowStyles = 'bg-emerald-500/5 hover:bg-emerald-500/10 border-l-4 border-l-emerald-500';
                                        $rankStyles = 'bg-emerald-500/20 text-emerald-400 font-bold';
                                        $teamStyles = 'text-emerald-100';
                                    } elseif ($row['status'] === 'eliminated') {
                                        $rowStyles = 'bg-red-500/5 hover:bg-red-500/10 opacity-70';
                                        $rankStyles = 'bg-red-500/20 text-red-500';
                                        $teamStyles = 'text-red-200';
                                    }
                                @endphp

                                <tr class="{{ $rowStyles }}">
                                    <td class="whitespace-nowrap py-3 pl-4 pr-3 sm:pl-6 text-center">
                                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-md {{ $rankStyles }} text-xs">
                                            {{ $row['position'] }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm flex items-center gap-3">
                                        @if($row['team']->image)
                                            <img src="{{ asset('storage/' . $row['team']->image) }}" class="h-8 w-8 rounded-full border border-dark-border object-cover">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-400 border border-dark-border">{{ substr($row['team']->name, 0, 1) }}</div>
                                        @endif
                                        <span class="font-bold {{ $teamStyles }}">{{ $row['team']->name }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-slate-300 text-center">{{ $row['pj'] }}</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-brand-400 text-center font-bold">{{ $row['pg'] }}</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-slate-400 text-center">{{ $row['pe'] }}</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-red-400 text-center font-bold">{{ $row['pp'] }}</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-slate-500 text-center hidden sm:table-cell">{{ $row['cf'] }}</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-slate-500 text-center hidden sm:table-cell">{{ $row['cc'] }}</td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-slate-400 text-center">
                                        @if($row['dif'] > 0)
                                            <span class="text-emerald-400">+{{ $row['dif'] }}</span>
                                        @elseif($row['dif'] < 0)
                                            <span class="text-red-400">{{ $row['dif'] }}</span>
                                        @else
                                            <span>0</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-base font-black text-amber-400 text-center bg-dark-bg/20 border-l border-dark-border/50">
                                        {{ $row['pts'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-dark-bg px-4 py-3 border-t border-dark-border flex items-center gap-6 text-xs text-slate-400 justify-center">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-sm border border-emerald-500 bg-emerald-500/20"></span> Zona Clasificación
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-sm border border-red-500 bg-red-500/20"></span> Eliminados matemáticamente
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>

@endsection