@extends('layouts.public')

@section('content')
<div class="flex min-h-[70vh] items-center justify-center p-4">
    <div class="w-full max-w-md bg-dark-card border border-dark-border rounded-xl shadow-2xl p-8 relative overflow-hidden">
        
        <!-- Glow effects -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-brand-500/20 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-brand-500/10 blur-3xl rounded-full"></div>

        <div class="relative z-10">
            <div class="text-center mb-8">
                <div class="inline-flex h-14 w-14 bg-brand-500 rounded-2xl items-center justify-center shadow-lg shadow-brand-500/30 mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Acceso Administrativo</h2>
                <p class="text-slate-400 text-sm mt-2">Inicia sesión para gestionar torneos y pizarras virtuales.</p>
            </div>

            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="w-full pl-10 pr-4 py-2 bg-dark-bg border border-dark-border rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors placeholder-slate-500"
                               placeholder="admin@softball.saas" value="{{ old('email') }}">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="w-full pl-10 pr-4 py-2 bg-dark-bg border border-dark-border rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors placeholder-slate-500"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 bg-dark-bg border-dark-border rounded text-brand-500 focus:ring-brand-500 focus:ring-offset-dark-card mt-0.5">
                        <label for="remember" class="ml-2 block text-sm text-slate-400">
                            Mantener sesión iniciada
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-brand-400 hover:text-brand-300">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>

                <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand-600 hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 focus:ring-offset-dark-card transition-colors">
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
