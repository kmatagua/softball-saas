@extends('layouts.app')

@section('content')

<!-- Header League Profile -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        @if($league->image)
            <img src="{{ asset('storage/' . $league->image) }}" class="h-16 w-16 rounded-xl object-cover ring-2 ring-white/10 shadow-lg" alt="{{ $league->name }}">
        @else
            <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 ring-2 ring-white/10 flex items-center justify-center shadow-lg">
                <span class="text-2xl font-black text-white tracking-wider">
                    {{ strtoupper(substr($league->name, 0, 2)) }}
                </span>
            </div>
        @endif
        
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-1">
                <a href="{{ route('admin.leagues.index') }}" class="hover:text-brand-300 transition-colors">Mis Ligas</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Workspace</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white line-clamp-1">{{ $league->name }}</h1>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('admin.tournaments.create', $league) }}" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 transition-all">
            <svg class="mr-2 -ml-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            Crear Torneo (Categoría)
        </a>
    </div>
</div>

<!-- Tournaments Selection Grid -->
<div class="mt-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Selecciona un Torneo para trabajar
        </h2>
    </div>

    @if($league->tournaments->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($league->tournaments as $tournament)
                <!-- Tarjeta Principal Clickable del Torneo -->
                <div class="relative group bg-dark-card rounded-2xl border border-dark-border shadow-md hover:shadow-xl hover:border-brand-500/50 transition-all duration-300 flex flex-col overflow-hidden">
                    
                    <!-- Acciones Secundarias (Editar Configuración general) -->
                    <div class="absolute top-4 right-4 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex items-center gap-1 bg-dark-card/90 backdrop-blur-sm p-1 rounded-lg border border-dark-border shadow-sm">
                            <a href="{{ route('admin.tournaments.edit', [$league, $tournament]) }}" class="p-1.5 text-slate-400 hover:text-white rounded-md hover:bg-dark-hover transition-colors" title="Editar Configuración">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Enlace de Área Clickable (Lleva al Centro de Control de este Torneo) -->
                    <a href="{{ route('admin.tournaments.show', [$league, $tournament]) }}" class="flex-1 p-6 flex flex-col h-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand-500 rounded-2xl">
                        
                        <!-- Header del Torneo en Tarjeta -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="h-12 w-12 rounded-lg bg-dark-bg border border-dark-border flex items-center justify-center group-hover:bg-brand-500/10 group-hover:border-brand-500/30 transition-colors">
                                <svg class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $tournament->is_active ? 'bg-emerald-400/10 text-emerald-400 ring-emerald-400/20' : 'bg-slate-400/10 text-slate-400 ring-slate-400/20' }}">
                                    {{ $tournament->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>

                        <!-- Info -->
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-brand-400 transition-colors line-clamp-2">{{ $tournament->name }}</h3>
                        
                        <div class="mt-auto pt-4 border-t border-dark-border/50">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-400">Grupos:</span>
                                    <span class="font-medium text-white">{{ $tournament->groups_count }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-400">Total Equipos:</span>
                                    @php
                                        // Suponiendo que queramos contar equipos en base a la relación a través de grupos
                                        // o directamente de tournament->teams() si existe esa relación directa
                                        $totalTeamsCount = $tournament->groups->sum(function($group) { return $group->teams->count(); });
                                    @endphp
                                    <span class="font-medium text-white">{{ $totalTeamsCount }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-400">Clasifican por G.:</span>
                                    <span class="font-medium text-brand-400">{{ $tournament->qualifies_per_group }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Acción Sutil de Hover -->
                        <div class="mt-4 pt-4 border-t border-dark-border flex items-center justify-between text-sm font-semibold text-brand-500 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span>Ingresar al Torneo</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="col-span-full border-2 border-dashed border-dark-border rounded-xl p-16 text-center bg-dark-card/50">
            <div class="mx-auto h-20 w-20 rounded-full bg-dark-bg flex items-center justify-center mb-6 ring-1 ring-dark-border shadow-inner">
                <svg class="h-10 w-10 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white">No hay torneos iniciados</h3>
            <p class="mt-2 text-base text-slate-400 max-w-md mx-auto">Para comenzar a operar esta Liga, necesitas crear al menos un torneo (Ej: Categoría Libre, Veteranos, etc).</p>
        </div>
    @endif
</div>

@endsection