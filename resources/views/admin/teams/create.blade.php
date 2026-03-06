@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
                <span class="text-slate-500">/</span>
                <a href="{{ route('admin.tournaments.show', [$league, $tournament]) }}" class="hover:text-brand-300 transition-colors">{{ $tournament->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Nuevo Equipo</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Inscribir Equipo</h1>
            <p class="mt-1 text-sm text-slate-400">Registra un nuevo equipo para el torneo <span class="text-brand-300 font-semibold">{{ $tournament->name }}</span>.</p>
        </div>
        <a href="{{ route('admin.tournaments.show', [$league, $tournament]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
            Volver al Torneo
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Column -->
        <div class="lg:col-span-2">
            <div class="bg-dark-card rounded-xl border border-dark-border shadow-sm overflow-hidden p-6">
                <form method="POST" action="{{ route('admin.teams.store', [$league, $tournament]) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Nombre de Equipo -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Nombre de la Franquicia o Equipo</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" class="block w-full rounded-lg border-0 py-2.5 pl-10 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required placeholder="Ej. Los Tigres, Cardenales BaseC..." autofocus autocomplete="off">
                        </div>
                    </div>

                    <!-- Asignación de Grupo -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Grupo Clasificatorio</label>
                        <select name="group_id" class="block w-full rounded-lg border-0 py-2.5 pl-3 pr-10 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" onchange="window.location.href='?group=' + this.value">
                            <option value="">-- Sin Grupo (Mostrar todos los creados recientemente) --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ (old('group_id') ?? request('group')) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-slate-400">Si cambias de grupo, la lista de equipos de la derecha se actualizará automáticamente.</p>
                    </div>

                    <!-- Logotipo -->
                    <div class="pt-4 border-t border-dark-border mt-6">
                        <label class="block text-sm font-medium text-white mb-4">Logotipo del Equipo (Opcional)</label>
                        <div class="flex items-center gap-6 p-4 rounded-xl border border-dark-border bg-dark-bg/50">
                            <div class="h-24 w-24 rounded-lg bg-dark-card border border-dark-border flex items-center justify-center overflow-hidden">
                                <svg class="mx-auto h-10 w-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>

                            <div class="flex-1">
                                <input type="file" name="image" id="image" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-dark-hover file:text-brand-400 hover:file:bg-dark-bg hover:file:text-brand-300 transition-colors bg-dark-bg ring-1 ring-inset ring-dark-border rounded-lg" accept="image/*">
                                <p class="mt-2 text-xs text-slate-500">Imágenes PNG, JPG preferiblemente con transparencia. Max 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between gap-x-4 border-t border-dark-border pt-6">
                        <a href="{{ route('admin.tournaments.show', [$league, $tournament]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-slate-300 hover:text-white bg-dark-bg hover:bg-dark-hover border border-dark-border transition-colors">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Finalizar Inscripciones
                        </a>
                        
                        <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-[0_0_15px_rgba(5,150,105,0.3)] hover:bg-emerald-500 hover:shadow-[0_0_20px_rgba(5,150,105,0.5)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-all">
                            Guardar e Inscribir Otro
                            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar List Column -->
        <div class="lg:col-span-1 border-l-0 lg:border-l border-dark-border pl-0 lg:pl-8">
            <h3 class="text-sm font-bold text-slate-300 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Equipos Ya Inscritos
            </h3>
            
            @if($selectedGroupId)
                <p class="text-xs text-slate-500 mb-4">Mostrando equipos en el grupo seleccionado actualmente.</p>
                
                @if($alreadyCreatedTeams->count() > 0)
                    <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($alreadyCreatedTeams as $team)
                            <div class="flex items-center gap-3 p-3 bg-dark-card border border-dark-border rounded-lg shadow-sm">
                                @if($team->image)
                                    <img src="{{ asset('storage/' . $team->image) }}" class="h-8 w-8 rounded-full object-cover ring-2 ring-dark-bg">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-slate-600 to-slate-800 ring-2 ring-dark-bg flex items-center justify-center text-xs text-white font-bold">
                                        {{ strtoupper(substr($team->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $team->name }}</p>
                                    <p class="text-[10px] text-slate-400">Registrado {{ $team->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-6 bg-dark-card rounded-lg border border-dashed border-dark-border">
                        <p class="text-sm text-slate-400">Aún no hay equipos inscritos en este grupo.</p>
                    </div>
                @endif
            @else
                <div class="text-center p-6 bg-dark-bg/50 rounded-lg border border-dashed border-dark-border">
                    <svg class="mx-auto h-8 w-8 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                    </svg>
                    <p class="text-xs text-slate-400">Selecciona un grupo a la izquierda para ver los equipos ya inscritos en él.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar for the team list */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #334155; 
    border-radius: 20px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #475569; 
}
</style>

@endsection