@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
            <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
            <span class="text-slate-500">/</span>
            <span class="text-slate-300">Equipos</span>
        </div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Directorio de Equipos</h1>
        <p class="mt-1 text-sm text-slate-400">Listado general de todos los equipos inscritos en los torneos de la liga.</p>
    </div>
</div>

<div class="bg-dark-card rounded-xl border border-dark-border shadow-sm overflow-hidden">
    @if($teams->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/50 border-b border-dark-border">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium">Equipo</th>
                        <th scope="col" class="px-6 py-4 font-medium">Torneo</th>
                        <th scope="col" class="px-6 py-4 font-medium">Grupo</th>
                        <th scope="col" class="px-6 py-4 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border bg-dark-bg/50">
                    @foreach($teams as $team)
                        <tr class="hover:bg-dark-hover/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($team->image)
                                        <img src="{{ asset('storage/' . $team->image) }}" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-xs text-white font-bold">
                                            {{ substr($team->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="font-medium text-white">{{ $team->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                {{ $team->tournament->name ?? 'Sin Torneo' }}
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                {{ $team->group->name ?? 'Sin Grupo' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="inline-flex items-center text-brand-400 hover:text-brand-300 font-medium">
                                    Ver Ficha →
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="text-lg font-medium text-white mb-1">No hay equipos</h3>
            <p class="text-slate-400 text-sm">Los equipos se crean desde la configuración de cada Torneo.</p>
        </div>
    @endif
</div>
@endsection
