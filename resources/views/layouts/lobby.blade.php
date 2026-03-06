<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Softball SaaS - Espacios de Trabajo</title>

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

<body class="bg-dark-bg text-slate-200 antialiased font-sans min-h-screen flex flex-col">

    <!-- Header Superior para Vista General (Workspace Selector) -->
    <header class="bg-dark-card border-b border-dark-border py-4 px-6 md:px-8 flex flex-col sm:flex-row gap-4 justify-between items-center shadow-sm relative z-20">
        <a href="/" class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
            <svg class="w-8 h-8 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
            </svg>
            Softball<span class="text-brand-500">SaaS</span>
        </a>

        <!-- Perfil en el Topbar -->
        <div class="flex items-center gap-6">
            <a href="{{ route('public.home') }}" class="hidden sm:flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                Fan Zone
            </a>
            <div class="h-6 w-px bg-dark-border hidden sm:block"></div>
            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-medium text-white">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</p>
                    <p class="text-xs text-slate-400">{{ auth()->check() ? auth()->user()->email : 'admin@softball.saas' }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-brand-600 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-dark-border">
                    {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}
                </div>
                @auth
                <form method="POST" action="{{ route('logout') }}" class="ml-1">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-red-400 bg-dark-bg hover:bg-dark-hover rounded-lg transition-colors border border-dark-border" title="Cerrar Sesión">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-4 md:p-8 relative">
        
        <!-- Decorative Background Blob para la vista global -->
        <div class="absolute top-0 right-1/4 -mt-20 -mr-20 w-96 h-96 bg-brand-600/10 rounded-full blur-3xl pointer-events-none z-0"></div>
        <div class="absolute bottom-0 left-1/4 -mb-20 -ml-20 w-64 h-64 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none z-0"></div>

        <div class="max-w-7xl mx-auto relative z-10 pt-4">
            @yield('content')
        </div>
    </main>

</body>
</html>
