@extends('layouts.lobby')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-white flex items-center gap-3">
            <svg class="h-8 w-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            Dashboard de Anotador
        </h1>
        <p class="mt-2 text-sm text-slate-400">Selecciona un juego asignado para comenzar o continuar llevando el score.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($games as $game)
        <div class="bg-dark-card border border-dark-border rounded-xl shadow-lg hover:border-brand-500/50 hover:shadow-brand-500/10 transition-all duration-300 overflow-hidden flex flex-col items-center">
            
            <div class="p-6 text-center w-full">
                <!-- Status Badge -->
                <div class="mb-4">
                    @if($game->status === \App\Enums\GameStatus::LIVE || $game->status === 'live' || (is_object($game->status) && $game->status->value === 'live'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold leading-5 bg-red-500/10 text-red-400 border border-red-500/20">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            EN VIVO
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold leading-5 bg-sky-500/10 text-sky-400 border border-sky-500/20">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            PROGRAMADO
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between w-full mb-6">
                    <!-- Home Team -->
                    <div class="flex flex-col items-center w-5/12">
                        @if($game->homeTeam->logo)
                            <img src="{{ asset('storage/' . $game->homeTeam->logo) }}" class="h-16 w-16 object-contain rounded-full bg-dark-bg p-1 border border-dark-border mb-2">
                        @else
                            <div class="h-16 w-16 rounded-full bg-dark-bg border border-dark-border flex items-center justify-center mb-2">
                                <span class="text-xl font-bold text-slate-500">{{ substr($game->homeTeam->name, 0, 2) }}</span>
                            </div>
                        @endif
                        <span class="font-bold text-white text-sm text-center leading-tight line-clamp-2">
                            {{ $game->homeTeam->name }}
                        </span>
                    </div>

                    <div class="w-2/12 flex items-center justify-center">
                        <span class="text-xl font-black text-slate-600 bg-dark-bg px-2 rounded">VS</span>
                    </div>

                    <!-- Away Team -->
                    <div class="flex flex-col items-center w-5/12">
                         @if($game->awayTeam->logo)
                            <img src="{{ asset('storage/' . $game->awayTeam->logo) }}" class="h-16 w-16 object-contain rounded-full bg-dark-bg p-1 border border-dark-border mb-2">
                        @else
                            <div class="h-16 w-16 rounded-full bg-dark-bg border border-dark-border flex items-center justify-center mb-2">
                                <span class="text-xl font-bold text-slate-500">{{ substr($game->awayTeam->name, 0, 2) }}</span>
                            </div>
                        @endif
                        <span class="font-bold text-white text-sm text-center leading-tight line-clamp-2">
                            {{ $game->awayTeam->name }}
                        </span>
                    </div>
                </div>
                
                <div class="text-xs text-slate-400 mt-2 bg-dark-bg/50 rounded p-2">
                    <p class="font-medium text-slate-300">{{ $game->league->name }} - {{ $game->tournament->name }}</p>
                    <div class="flex items-center justify-center gap-2 mt-1">
                        @if($game->game_date)
                            <span class="flex items-center gap-1"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ \Carbon\Carbon::parse($game->game_date)->format('d M - g:i A') }}</span>
                        @endif
                        @if($game->location)
                            <span class="text-slate-500 hidden sm:inline">•</span>
                            <span class="flex items-center gap-1"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $game->location }}</span>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Action Button Area Covering Full Width -->
            <a href="{{ route('admin.scorekeeper.index', [$game->league_id, $game->tournament_id, $game->id]) }}" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-bold py-3 text-sm flex items-center justify-center transition-colors group">
                <svg class="h-5 w-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Asignarse a Juego y Anotar
            </a>
        </div>
    @empty
        <div class="col-span-full py-16 text-center border-2 border-dashed border-dark-border rounded-xl">
            <svg class="mx-auto h-12 w-12 text-slate-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="text-lg font-bold text-white mb-1">No hay juegos disponibles</h3>
            <p class="text-sm text-slate-400">Actualmente no hay juegos programados o en vivo asignados a este anotador.</p>
        </div>
    @endforelse
</div>
@endsection
