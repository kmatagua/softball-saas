@extends('layouts.public')

@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <a href="{{ route('public.home') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors mb-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Volver a la cartelera
        </a>
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
            {{ $game->awayTeam->name }} vs {{ $game->homeTeam->name }}
        </h1>
        <p class="text-brand-400 text-sm font-medium mt-1">{{ $game->tournament->name ?? 'Torneo' }} • {{ $game->league->name ?? 'Liga' }}</p>
    </div>
    
    <div class="flex items-center gap-3 bg-dark-card/50 px-4 py-2 rounded-lg border border-dark-border">
        <span class="flex h-2.5 w-2.5 relative">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
        </span>
        <span class="text-sm font-medium text-slate-300">Actualización en vivo</span>
    </div>
</div>

<!-- Contenedor Vue de Solo Lectura -->
<div id="fan-view-app" v-cloak></div>

@endsection

@push('scripts')
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script type="importmap">
      {
        "imports": {
          "vue": "https://unpkg.com/vue@3/dist/vue.esm-browser.js"
        }
      }
    </script>

    <script type="module">
        import { mountFanView } from '{{ asset('js/scorekeeper/fan.js') }}';

        const apiEndpoints = {
            fetchGameDetailsUrl: '/api/games/{{ $game->id }}'
        };

        mountFanView('#fan-view-app', apiEndpoints);
    </script>
@endpush
