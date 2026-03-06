@extends('layouts.public')

@section('content')

<div class="mb-10 text-center">
    <h1 class="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight">Acción en <span class="text-brand-500">Tiempo Real</span></h1>
    <p class="text-lg text-slate-400 max-w-2xl mx-auto">Sigue los partidos de softball en vivo, consulta resultados anteriores y descubre a los líderes del torneo.</p>
</div>

<!-- Ligas Activas -->
<div id="ligas" class="mb-12 scroll-mt-24">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Ligas Destacadas
        </h2>
    </div>

    @if($leagues->isEmpty())
        <div class="bg-dark-card border border-dark-border rounded-xl p-12 text-center text-slate-400">
            <svg class="mx-auto h-12 w-12 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-lg">No hay ligas activas en este momento.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($leagues as $league)
                <div class="bg-dark-card border border-dark-border rounded-xl p-6 hover:border-brand-500/50 transition-colors group cursor-pointer relative overflow-hidden">
                    <!-- Glow effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="relative">
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-brand-400 transition-colors">{{ $league->name }}</h3>
                        <p class="text-sm text-slate-400 mb-4">{{ $league->description ?? 'Sin descripción.' }}</p>
                        
                        <div class="flex items-center gap-4 text-sm text-slate-300">
                            <span class="flex items-center gap-1.5 bg-slate-800 px-2.5 py-1 rounded-md border border-slate-700">
                                <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                {{ $league->tournaments()->count() }} Torneos
                            </span>
                            <span class="flex items-center gap-1.5 bg-slate-800 px-2.5 py-1 rounded-md border border-slate-700">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                En Vivo
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Juegos en Vivo (Mock Data Placeholder por ahora) -->
<div>
    <h2 class="text-2xl font-bold text-white flex items-center gap-2 mb-6">
        <svg class="w-6 h-6 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Próximos Partidos & Live
    </h2>
    
    <div class="bg-dark-card border border-dark-border rounded-xl p-8 flex flex-col items-center justify-center text-center">
        <div class="h-16 w-16 bg-slate-800 rounded-full flex items-center justify-center mb-4 border border-slate-700">
            <svg class="w-8 h-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-white mb-2">No hay partidos en vivo</h3>
        <p class="text-slate-400 max-w-sm">Vuelve más tarde para seguir el Box Score y la pizarra en tiempo real de los próximos encuentros.</p>
    </div>
</div>

@endsection
