<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Softball SaaS</title>

    <!-- Tailwind CSS (Play CDN for rapid prototyping without NPM) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        },
                        dark: {
                            bg: '#0f172a',    // slate-900
                            card: '#1e293b',  // slate-800
                            hover: '#334155', // slate-700
                            border: '#475569' // slate-600
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-dark-bg text-slate-200 antialiased font-sans min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-dark-card border-r border-dark-border flex-shrink-0 flex flex-col">
        <div class="p-6 border-b border-dark-border">
            <a href="/" class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                <svg class="w-8 h-8 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                </svg>
                Softball<span class="text-brand-500">SaaS</span>
            </a>
        </div>
        
        <nav class="flex-1 p-4 space-y-2">
            <!-- Link Público -->
            <a href="{{ route('public.home') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-slate-400 hover:bg-dark-hover hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                Fan Zone (En Vivo)
            </a>
            
            <hr class="border-dark-border my-2">

            <!-- Link Ligas -->
            <a href="{{ route('admin.leagues.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs(['admin.leagues.*']) && !isset($league) ? 'bg-dark-hover text-white' : 'text-slate-400 hover:bg-dark-hover hover:text-white transition-colors' }}">
                <svg class="w-5 h-5 {{ request()->routeIs(['admin.leagues.*']) && !isset($league) ? 'text-brand-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                {{ isset($league) && $league ? 'Volver a Ligas' : 'Gestión de Ligas' }}
            </a>

            <!-- Rutas Condicionales por Liga -->
            @if(isset($league) && $league)
                <a href="{{ route('admin.tournaments.index', $league) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.tournaments.*') ? 'bg-dark-hover text-white' : 'text-slate-400 hover:bg-dark-hover hover:text-white transition-colors' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.tournaments.*') ? 'text-brand-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Torneos (Categorías)
                </a>

                <a href="{{ route('admin.teams.index', $league) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs(['admin.teams.*', 'admin.groups.*']) ? 'bg-dark-hover text-white' : 'text-slate-400 hover:bg-dark-hover hover:text-white transition-colors' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs(['admin.teams.*', 'admin.groups.*']) ? 'text-brand-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Equipos
                </a>

                <a href="{{ route('admin.players.index', $league) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.players.*') ? 'bg-dark-hover text-white' : 'text-slate-400 hover:bg-dark-hover hover:text-white transition-colors' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.players.*') ? 'text-brand-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Jugadores
                </a>

                <a href="{{ route('admin.games.leagueIndex', $league) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.games.*') ? 'bg-dark-hover text-white' : 'text-slate-400 hover:bg-dark-hover hover:text-white transition-colors' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.games.*') ? 'text-brand-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Juegos
                </a>
            @else
                <!-- Placeholders inactivos si no hay liga seleccionada -->
                <div title="Selecciona una Liga primero" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-slate-500 cursor-not-allowed transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Equipos
                </div>

                <div title="Selecciona una Liga primero" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-slate-500 cursor-not-allowed transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Juegos
                </div>
            @endif
        </nav>
        
        <div class="p-4 border-t border-dark-border">
            <div class="flex items-center justify-between px-2 py-2">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-brand-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ auth()->check() ? auth()->user()->email : 'admin@softball.saas' }}</p>
                    </div>
                </div>
                
                @auth
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-400/10 rounded-md transition-colors" title="Cerrar Sesión">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-h-0 overflow-hidden">
        
        <!-- Mobile Header (Visible only on small screens) -->
        <header class="md:hidden bg-dark-card border-b border-dark-border p-4 flex justify-between items-center">
            <span class="text-xl font-bold text-white">Softball<span class="text-brand-500">SaaS</span></span>
            <button class="text-slate-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </header>

        <!-- Dynamic View Content -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

</body>
</html>