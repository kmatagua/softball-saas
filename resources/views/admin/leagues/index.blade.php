@extends('layouts.lobby')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Gestión de Ligas</h1>
            <p class="mt-1 text-sm text-slate-400">Organiza y administra todos los torneos activos.</p>
        </div>
        <a href="{{ route('admin.leagues.create') }}" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 transition-all">
            <svg class="mr-2 -ml-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            Crear Nueva Liga
        </a>
    </div>

    <!-- Lista de Espacios de Trabajo (Ligas) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($leagues as $league)
            <!-- Tarjeta Principal Clickable -->
            <div class="relative group bg-dark-card rounded-2xl border border-dark-border shadow-md hover:shadow-xl hover:border-brand-500/50 transition-all duration-300 flex flex-col overflow-hidden">
                
                <!-- Acciones Secundarias Ocultas / Dropdown Simulado -->
                <div class="absolute top-4 right-4 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="flex items-center gap-1 bg-dark-card/90 backdrop-blur-sm p-1 rounded-lg border border-dark-border shadow-sm">
                        <a href="{{ route('admin.leagues.edit', $league) }}" class="p-1.5 text-slate-400 hover:text-white rounded-md hover:bg-dark-hover transition-colors" title="Editar Configuración">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                        <form action="{{ route('admin.leagues.destroy', $league) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar el espacio de trabajo de esta liga?')" class="p-1.5 text-slate-400 hover:text-red-400 rounded-md hover:bg-dark-hover transition-colors" title="Eliminar Liga">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Enlace de Área Clickable (Cubre toda la tarjeta salvo el z-10 de los botones) -->
                <a href="{{ route('admin.leagues.show', $league) }}" class="flex-1 p-6 flex flex-col items-center text-center focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand-500 rounded-2xl">
                    
                    <!-- Avatar/Logo de la Liga -->
                    <div class="mb-5 relative">
                        <div class="absolute inset-0 bg-brand-500 blur-xl opacity-20 rounded-full group-hover:opacity-40 transition-opacity"></div>
                        @if($league->image)
                            <img src="{{ asset('storage/' . $league->image) }}" class="relative h-24 w-24 rounded-full object-cover ring-4 ring-dark-bg group-hover:ring-brand-500/30 transition-all shadow-lg" alt="{{ $league->name }}">
                        @else
                            <div class="relative h-24 w-24 rounded-full bg-slate-800 ring-4 ring-dark-bg group-hover:ring-brand-500/30 transition-all shadow-lg flex items-center justify-center">
                                <span class="text-3xl font-black text-brand-400 tracking-wider">
                                    {{ strtoupper(substr($league->name, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Status Indicator Overlay -->
                        <div class="absolute bottom-0 right-0 h-5 w-5 rounded-full ring-4 ring-dark-card flex items-center justify-center {{ $league->active ? 'bg-green-500' : 'bg-slate-500' }}" title="{{ $league->active ? 'Liga Activa' : 'Liga Finalizada' }}">
                        </div>
                    </div>

                    <!-- Identificación -->
                    <h3 class="text-xl font-bold text-white mb-1 group-hover:text-brand-400 transition-colors line-clamp-2 leading-tight">{{ $league->name }}</h3>
                    
                    <!-- Stats / Meta -->
                    <div class="mt-4 flex items-center justify-center gap-3 text-sm text-slate-400">
                        <span class="flex items-center gap-1.5 py-1 px-3 rounded-full bg-dark-bg border border-dark-border">
                            <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="font-medium text-slate-300">{{ $league->teams()->count() }}</span> Equipos
                        </span>
                    </div>

                    <!-- Call to Action sutil de Hover -->
                    <div class="mt-6 text-sm font-semibold text-brand-500 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                        Ingresar al Área de Trabajo 
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full border-2 border-dashed border-dark-border rounded-xl p-16 text-center bg-dark-card/50">
                <div class="mx-auto h-20 w-20 rounded-full bg-dark-bg flex items-center justify-center mb-6 ring-1 ring-dark-border shadow-inner">
                    <svg class="h-10 w-10 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">No tienes Ligas Asignadas</h3>
                <p class="mt-2 text-base text-slate-400 max-w-md mx-auto">Crea tu primer espacio de trabajo para comenzar a configurar equipos, jugadores y organizar los torneos de la temporada.</p>
                <div class="mt-8">
                    <a href="{{ route('admin.leagues.create') }}" class="inline-flex items-center rounded-lg bg-brand-600 px-6 py-3 text-sm font-bold text-white shadow-lg hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 hover:shadow-brand-500/25 transition-all">
                        <svg class="mr-2 -ml-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Crear Mi Primer Espacio de Trabajo
                    </a>
                </div>
            </div>
        @endforelse
    </div>
@endsection