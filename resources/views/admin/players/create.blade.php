@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.leagues.show', $league) }}" class="hover:text-brand-300 transition-colors">{{ $league->name }}</a>
                <span class="text-slate-500">/</span>
                <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="hover:text-brand-300 transition-colors">{{ $team->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Nuevo Jugador</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Ficha de Jugador</h1>
            <p class="mt-1 text-sm text-slate-400">Afilia a un jugador en el roster oficial de <span class="text-white font-medium">{{ $team->name }}</span>.</p>
        </div>
        <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
            Volver al Equipo
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

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-brand-500/10 border border-brand-500/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-brand-400">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Column -->
        <div class="lg:col-span-2">
            <div class="bg-dark-card rounded-xl border border-dark-border shadow-sm overflow-hidden p-6">
                <form method="POST" action="{{ route('admin.players.store', [$league, $team]) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombres -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Nombres</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required placeholder="Nombres del jugador" autofocus autocomplete="off">
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Apellidos</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required placeholder="Apellidos del jugador" autocomplete="off">
                        </div>

                        <!-- DNI -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Cédula / DNI</label>
                            <input type="text" name="dni" value="{{ old('dni') }}" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" placeholder="Documento de identidad" autocomplete="off">
                        </div>

                        <!-- Jersey Number -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Número de Jersey</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 font-bold">#</span>
                                </div>
                                <input type="number" name="jersey_number" value="{{ old('jersey_number') }}" class="block w-full rounded-lg border-0 py-2.5 pl-8 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" placeholder="00" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <!-- Fotografía -->
                    <div class="pt-4 border-t border-dark-border mt-6">
                        <label class="block text-sm font-medium text-white mb-4">Fotografía de Rostro (Opcional)</label>
                        <div class="flex items-center gap-6 p-4 rounded-xl border border-dark-border bg-dark-bg/50">
                            <div class="h-28 w-28 rounded-full bg-dark-card border-4 border-dark-border flex items-center justify-center overflow-hidden shadow-lg ring-2 ring-brand-500/20 relative group">
                                <img id="imagePreview" src="#" alt="Preview" class="h-full w-full object-cover hidden">
                                <svg id="imagePlaceholder" class="h-14 w-14 text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('image').click()">
                                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                            </div>

                            <div class="flex-1">
                                <input type="file" name="image" id="image" onchange="previewImage(event)" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-dark-hover file:text-brand-400 hover:file:bg-dark-bg hover:file:text-brand-300 transition-colors bg-dark-bg ring-1 ring-inset ring-dark-border rounded-lg" accept="image/*">
                                <p class="mt-2 text-xs text-slate-500">Imágenes PNG, JPG preferiblemente enfocadas al rostro. Max 10MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between gap-x-4 border-t border-dark-border pt-6">
                        <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-slate-300 hover:text-white bg-dark-bg hover:bg-dark-hover border border-dark-border transition-colors">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Finalizar Inscripciones
                        </a>
                        
                        <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-[0_0_15px_rgba(5,150,105,0.3)] hover:bg-emerald-500 hover:shadow-[0_0_20px_rgba(5,150,105,0.5)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-all">
                            Guardar y Afiliar Otro
                            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar List Column -->
        <div class="lg:col-span-1 border-l-0 lg:border-l border-dark-border pl-0 lg:pl-8">
            <h3 class="text-sm font-bold text-slate-300 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Jugadores del Equipo
            </h3>
            
            <p class="text-xs text-slate-500 mb-4">Mostrando jugadores de <span class="text-white">{{ $team->name }}</span>.</p>
            
            @if($alreadyCreatedPlayers->count() > 0)
                <div class="space-y-3 max-h-[550px] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($alreadyCreatedPlayers as $player)
                        <div class="flex items-center gap-3 p-3 bg-dark-card border border-dark-border rounded-lg shadow-sm">
                            <div class="relative">
                                @if($player->image)
                                    <img src="{{ asset('storage/' . $player->image) }}" class="h-10 w-10 rounded-full object-cover border-2 border-dark-border">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-slate-600 to-slate-800 border-2 border-dark-border flex items-center justify-center text-sm text-white font-bold">
                                        {{ strtoupper(substr($player->first_name, 0, 1) . substr($player->last_name, 0, 1)) }}
                                    </div>
                                @endif
                                
                                @if($player->jersey_number)
                                    <div class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full bg-brand-600 flex items-center justify-center text-[10px] font-bold text-white border-2 border-dark-card shadow-sm">
                                        {{ $player->jersey_number }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $player->first_name }} {{ $player->last_name }}</p>
                                <p class="text-[10px] text-slate-400">
                                    {{ $player->dni ? 'DNI: '.$player->dni : 'Sin documento' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center p-6 bg-dark-card rounded-lg border border-dashed border-dark-border">
                    <p class="text-sm text-slate-400">Aún no hay jugadores inscritos en este equipo.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar for the player list */
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

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imagePreview');
            const placeholder = document.getElementById('imagePlaceholder');
            output.src = reader.result;
            output.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>

@endsection