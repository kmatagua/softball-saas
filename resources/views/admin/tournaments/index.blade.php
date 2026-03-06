@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Torneos</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Torneos / Categorías</h1>
            <p class="mt-1 text-sm text-slate-400">Listado de todos los torneos activos e inactivos de la liga.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.leagues.show', $league) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
                Volver a Liga
            </a>
            <a href="{{ route('admin.tournaments.create', $league) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-brand-600 hover:bg-brand-500 transition-colors shadow-sm">
                <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Crear Torneo
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-emerald-500/10 border border-emerald-500/20 p-4 flex">
            <svg class="h-5 w-5 text-emerald-400 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
            <p class="text-sm font-medium text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    @if($tournaments->isEmpty())
        <div class="text-center py-12 bg-dark-card rounded-xl border border-dark-border border-dashed">
            <svg class="mx-auto h-12 w-12 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-white">No hay torneos</h3>
            <p class="mt-1 text-sm text-slate-400">Comienza creando un torneo o categoría para esta liga.</p>
        </div>
    @else
        <div class="bg-dark-card rounded-xl border border-dark-border overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-dark-border">
                    <thead class="bg-dark-bg text-left">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-xs font-semibold text-slate-300 uppercase tracking-wider sm:pl-6">Nombre de Torneo</th>
                            <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-300 uppercase tracking-wider">Grupos</th>
                            <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-300 uppercase tracking-wider">Clasifican</th>
                            <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-300 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-border bg-dark-card">
                        @foreach($tournaments as $tournament)
                            <tr class="hover:bg-dark-hover transition-colors">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                    <a href="{{ route('admin.tournaments.show', [$league, $tournament]) }}" class="hover:text-brand-400 transition-colors">
                                        {{ $tournament->name }}
                                    </a>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-400">{{ $tournament->groups_count }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-400">{{ $tournament->qualifies_per_group }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @if($tournament->is_active)
                                        <span class="inline-flex items-center rounded-md bg-emerald-400/10 px-2 py-1 text-xs font-medium text-emerald-400 ring-1 ring-inset ring-emerald-400/20">Activo</span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-slate-400/10 px-2 py-1 text-xs font-medium text-slate-400 ring-1 ring-inset ring-slate-400/20">Inactivo</span>
                                    @endif
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.tournaments.show', [$league, $tournament]) }}" class="text-brand-400 hover:text-brand-300">Ver Detalles</a>
                                        <form action="{{ route('admin.tournaments.destroy', [$league, $tournament]) }}" method="POST" onsubmit="return confirm('¿Eliminar torneo por completo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

@endsection