@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Franquicia</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Editar Equipo</h1>
            <p class="mt-1 text-sm text-slate-400">Modifica los detalles de la franquicia y su grupo asignado.</p>
        </div>
        <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
            Cancelar
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
        <form method="POST" action="{{ route('admin.teams.update', [$league, $team]) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nombre de Equipo -->
            <div>
                <label class="block text-sm font-medium text-white mb-2">Nombre de la Franquicia o Equipo</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                    </div>
                    <input type="text" name="name" value="{{ old('name', $team->name) }}" class="block w-full rounded-lg border-0 py-2.5 pl-10 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required>
                </div>
            </div>

            <!-- Asignación de Grupo -->
            @if($groups->count())
            <div>
                <label class="block text-sm font-medium text-white mb-2">Grupo Clasificatorio</label>
                <select name="group_id" class="block w-full rounded-lg border-0 py-2.5 pl-3 pr-10 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6">
                    <option value="">-- Sin Grupo --</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ $team->group_id == $group->id ? 'selected' : '' }}>
                            {{ $group->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

             <!-- Logotipo -->
             <div class="pt-4 border-t border-dark-border mt-6">
                <label class="block text-sm font-medium text-white mb-4">Logotipo del Equipo</label>
                <div class="flex items-center gap-6 p-4 rounded-xl border border-dark-border bg-dark-bg/50">
                    <div class="h-24 w-24 rounded-lg bg-dark-card border border-dark-border flex items-center justify-center overflow-hidden">
                        @if($team->image)
                            <img src="{{ asset('storage/' . $team->image) }}" class="h-full w-full object-cover">
                        @else
                            <svg class="mx-auto h-10 w-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        @endif
                    </div>

                    <div class="flex-1">
                        <input type="file" name="image" id="image" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-dark-hover file:text-brand-400 hover:file:bg-dark-bg hover:file:text-brand-300 transition-colors bg-dark-bg ring-1 ring-inset ring-dark-border rounded-lg" accept="image/*">
                        <p class="mt-2 text-xs text-slate-500">{{ $team->image ? 'Sube una nueva imagen para sobreescribir el actual.' : 'Imágenes PNG, JPG preferiblemente con transparencia.' }} Max 2MB.</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-dark-border pt-6">
                <button type="submit" class="inline-flex items-center rounded-lg bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 transition-all">
                    Actualizar Información
                </button>
            </div>
        </form>
    </div>
</div>

@endsection