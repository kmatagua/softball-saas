@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Editar Torneo</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Editar Torneo: {{ $tournament->name }}</h1>
            <p class="mt-1 text-sm text-slate-400">Modifica la información básica del torneo y sus reglas de clasificación.</p>
        </div>
        <a href="{{ route('admin.tournaments.index', $league) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
            Volver
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

    <div class="bg-dark-card rounded-xl border border-dark-border shadow-sm overflow-hidden p-6">
        <form method="POST" action="{{ route('admin.tournaments.update', [$league, $tournament]) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium text-white mb-2">Nombre del Torneo / Categoría</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                    </div>
                    <input type="text" name="name" value="{{ old('name', $tournament->name) }}" class="block w-full rounded-lg border-0 py-2.5 pl-10 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required>
                </div>
            </div>

            <!-- Estructura de Grupos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-dark-border mt-6">
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Estado</label>
                    <div class="flex items-center h-10">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $tournament->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-dark-border bg-dark-bg text-brand-600 focus:ring-brand-500 focus:ring-offset-dark-card">
                        <label for="is_active" class="ml-2 block text-sm text-slate-300">Torneo Activo</label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-white mb-2">Equipos que clasifican a Playoffs</label>
                    <div class="flex rounded-md shadow-sm">
                        <span class="inline-flex items-center rounded-l-md border border-r-0 border-dark-border bg-dark-bg/50 px-3 text-slate-400 sm:text-sm">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </span>
                        <input type="number" name="qualifies_per_group" value="{{ old('qualifies_per_group', $tournament->qualifies_per_group) }}" min="1" class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-0 py-2 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required>
                    </div>
                     <p class="mt-2 text-xs text-slate-400">Por cada grupo (Ej: 4 clasifican).</p>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-dark-border pt-6">
                <button type="submit" class="inline-flex items-center rounded-lg bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 transition-all">
                    Actualizar Torneo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
