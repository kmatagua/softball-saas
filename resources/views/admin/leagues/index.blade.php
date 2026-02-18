@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Ligas</h3>

        <a href="{{ route('admin.leagues.create') }}" class="btn btn-primary">
            + Crear Liga
        </a>
    </div>

    <hr>

    @forelse($leagues as $league)

        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">

                    @if($league->image)
                        <img src="{{ asset('storage/' . $league->image) }}" width="60" height="60"
                            style="object-fit:cover;border-radius:8px;margin-right:15px;">
                    @else
                        <div style="width:60px;height:60px;
                                                background:#374151;
                                                border-radius:8px;
                                                margin-right:15px;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;">
                            🥎
                        </div>
                    @endif

                    <div>
                        <h5 class="mb-1">{{ $league->name }}</h5>
                        <small class="text-muted">
                            {{ $league->teams()->count() }} equipos
                        </small>
                    </div>

                </div>

                <div>
                    <a href="{{ route('admin.leagues.show', $league) }}" class="btn btn-sm btn-info">
                        Ver
                    </a>

                    <a href="{{ route('admin.leagues.edit', $league) }}" class="btn btn-sm btn-warning">
                        Editar
                    </a>

                    <form action="{{ route('admin.leagues.destroy', $league) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar liga?')">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @empty

        <div class="text-center mt-5">
            <h5 class="text-muted">No hay ligas creadas aún</h5>
            <p class="text-muted">
                Comienza creando tu primera liga para empezar a organizar el torneo.
            </p>

            <a href="{{ route('admin.leagues.create') }}" class="btn btn-primary mt-2">
                Crear Primera Liga
            </a>
        </div>

    @endforelse

@endsection