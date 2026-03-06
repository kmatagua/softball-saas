<!DOCTYPE html>
<html lang="es" class="h-full bg-dark-bg text-dark-text antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Softball SaaS - Fan Zone</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#052e16',
                        },
                        dark: {
                            bg: '#0f172a', /* slate-900 */
                            card: '#1e293b', /* slate-800 */
                            border: '#334155', /* slate-700 */
                            text: '#f8fafc', /* slate-50 */
                            muted: '#94a3b8', /* slate-400 */
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .glass-effect {
                @apply bg-dark-card/80 backdrop-blur-md border border-dark-border;
            }
        }
        [v-cloak] { display: none; }
    </style>
    @stack('styles')
</head>
<body class="h-full flex flex-col min-h-screen selection:bg-brand-500 selection:text-white">

    <!-- Navbar Público -->
    <header class="glass-effect sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="h-10 w-10 bg-brand-500 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-white block leading-none">Softball<span class="text-brand-400">Pro</span></span>
                        <span class="text-[10px] text-brand-500/80 uppercase tracking-widest font-semibold mt-0.5 block">Fan Zone</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('public.home') }}" class="text-white hover:text-brand-400 px-3 py-2 text-sm font-medium transition-colors">Inicio</a>
                    <a href="{{ route('public.home') }}#ligas" class="text-slate-300 hover:text-white px-3 py-2 text-sm font-medium transition-colors">Ligas Activas</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('admin.leagues.index') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Panel Admin</a>
                    @else
                        <a href="/login" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Iniciar Sesión</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-dark-border py-8 mt-auto text-center">
        <p class="text-dark-muted text-sm">&copy; {{ date('Y') }} Softball SaaS. Todos los derechos reservados.</p>
    </footer>

    @stack('scripts')
</body>
</html>
