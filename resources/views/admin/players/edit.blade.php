@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="hover:text-brand-300 transition-colors">{{ $team->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Roster Activo</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Editar Jugador</h1>
            <p class="mt-1 text-sm text-slate-400">Modificando los atributos de la ficha de identificación.</p>
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
        <form method="POST" action="{{ route('admin.players.update', [$league, $team, $player]) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombres -->
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Nombres</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $player->first_name) }}" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required>
                </div>

                <!-- Apellidos -->
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Apellidos</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $player->last_name) }}" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" required>
                </div>

                <!-- DNI -->
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Cédula / DNI</label>
                    <input type="text" name="dni" value="{{ old('dni', $player->dni) }}" class="block w-full rounded-lg border-0 py-2.5 px-3 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6">
                </div>

                <!-- Jersey Number -->
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Número de Jersey</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-slate-500 font-bold">#</span>
                        </div>
                        <input type="number" name="jersey_number" value="{{ old('jersey_number', $player->jersey_number) }}" class="block w-full rounded-lg border-0 py-2.5 pl-8 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6">
                    </div>
                </div>
            </div>

             <!-- Fotografía -->
             <div class="pt-4 border-t border-dark-border mt-6">
                <label class="block text-sm font-medium text-white mb-4">Fotografía de Rostro</label>
                <div class="flex items-center gap-6 p-4 rounded-xl border border-dark-border bg-dark-bg/50">
                    <div class="h-28 w-28 rounded-full bg-dark-card border-4 border-dark-border flex items-center justify-center overflow-hidden shadow-lg ring-2 ring-brand-500/20 relative group">
                        <img id="imagePreview" src="{{ $player->image ? asset('storage/' . $player->image) : '#' }}" alt="Preview" class="h-full w-full object-cover {{ $player->image ? '' : 'hidden' }}">
                        <svg id="imagePlaceholder" class="h-14 w-14 text-slate-600 {{ $player->image ? 'hidden' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('image').click()">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                    </div>

                    <div class="flex-1">
                        <input type="file" name="image" id="image" onchange="previewImage(event)" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-dark-hover file:text-brand-400 hover:file:bg-dark-bg hover:file:text-brand-300 transition-colors bg-dark-bg ring-1 ring-inset ring-dark-border rounded-lg" accept="image/*">
                        <p class="mt-2 text-xs text-slate-500">{{ $player->image ? 'Sobreescribir foto actual' : 'Imágenes PNG, JPG preferiblemente enfocadas al rostro.' }} Max 10MB.</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-dark-border pt-6">
                <button type="submit" class="inline-flex items-center rounded-lg bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 transition-all">
                    Actualizar Ficha
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imagePreview');
            const placeholder = document.getElementById('imagePlaceholder');
            output.src = reader.result;
            output.classList.remove('hidden');
            if(placeholder) placeholder.classList.add('hidden');
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>

@endsection