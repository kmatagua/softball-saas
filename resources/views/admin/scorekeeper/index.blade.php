@extends('layouts.app')

@push('styles')
    <!-- Aquí podríamos agregar CSS específico si es necesario -->
    <style>
        /* Ocultar sidebar en el scorekeeper para dar más espacio si se desea, o lo dejamos */
        [v-cloak] { display: none; }
    </style>
@endpush

@section('content')

<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-brand-400 mb-2">
                <a href="{{ route('admin.games.index', [$league, $tournament]) }}" class="hover:text-brand-300 transition-colors">{{ $tournament->name }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-slate-300">Partido En Vivo</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <svg class="h-8 w-8 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                Consola del Anotador
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium bg-emerald-500/10 text-emerald-400 ring-1 ring-inset ring-emerald-500/20">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                API Conectada
            </span>
            <a href="{{ route('admin.games.index', [$league, $tournament]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-dark-bg border border-dark-border hover:bg-dark-hover transition-colors">
                Salir del Juego
            </a>
        </div>
    </div>

    <!-- Contenedor Vue -->
    <div id="scorekeeper-app" v-cloak></div>

</div>

@endsection

@push('scripts')
    <!-- Módulos de Vue y Axios vía CDN -->
    <script type="importmap">
      {
        "imports": {
          "vue": "https://unpkg.com/vue@3/dist/vue.esm-browser.js",
          "axios": "https://cdn.jsdelivr.net/npm/axios@1.6.7/+esm"
        }
      }
    </script>

    <script type="module">
        import axios from 'axios';
        window.axios = axios;

        // Start Axios Config
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        } else {
            // Let's add it if it is missing
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
        }
        window.axios.defaults.withCredentials = true;
        // End Axios Config

        import { mountScorekeeper } from '{{ asset('js/scorekeeper/app.js') }}';

        // Rutas y configuración inicial que Laravel le pasa a Vue
        const apiEndpoints = {
            gameId: {{ $game->id }},
            leagueId: {{ $league->id }},
            tournamentId: {{ $tournament->id }},
            fetchGameDetailsUrl: '{{ route('api.games.show', ['id' => $game->id]) }}',
            postEventUrl: '/api/game-events'
        };

        mountScorekeeper('#scorekeeper-app', apiEndpoints);
    </script>
@endpush
