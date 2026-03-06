@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Crear Nueva Liga</h1>
            <p class="mt-1 text-sm text-slate-400">Las ligas organizan uno o múltiples torneos/categorías.</p>
        </div>
        <a href="{{ route('admin.leagues.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
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
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.leagues.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-white mb-2">Logo de la Liga (Opcional)</label>
                <div class="mt-2 flex justify-center rounded-lg border border-dashed border-dark-border px-6 py-10 hover:border-brand-500/50 transition-colors bg-dark-bg/50">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                        </svg>
                        <div class="mt-4 flex text-sm leading-6 text-slate-400 justify-center">
                            <label for="file-upload" class="relative cursor-pointer rounded-md font-semibold text-brand-500 hover:text-brand-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-brand-600 focus-within:ring-offset-2 focus-within:ring-offset-dark-bg">
                                <span>Subir un archivo</span>
                                <input id="file-upload" name="image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">o arrastra y suelta</p>
                        </div>
                        <p class="text-xs leading-5 text-slate-500">PNG, JPG hasta 5MB</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2">Nombre Comercial de la Liga</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <input type="text" name="name" class="block w-full rounded-lg border-0 py-2.5 pl-10 bg-dark-bg text-white ring-1 ring-inset ring-dark-border focus:ring-2 focus:ring-inset focus:ring-brand-500 sm:text-sm sm:leading-6" placeholder="Ej. Liga Central Nocturna" required value="{{ old('name') }}">
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-dark-border pt-6">
                <button type="submit" class="inline-flex items-center rounded-lg bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 transition-all">
                    Crear y Continuar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection