@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="d-flex align-items-center">

            @if($league->image)
                <img src="{{ asset('storage/' . $league->image) }}" width="80" height="80"
                    style="object-fit:cover;border-radius:12px;margin-right:20px;">
            @else
                <div style="width:80px;height:80px;
                                        background:#374151;
                                        border-radius:12px;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        margin-right:20px;
                                        font-size:28px;">
                    🥎
                </div>
            @endif

            <div>
                <h3 class="mb-0">{{ $league->name }}</h3>
                <small class="text-muted">
                    Panel de administración de la liga
                </small>
            </div>

        </div>

        <a href="{{ route('admin.leagues.index') }}" class="btn btn-outline-light btn-sm">
            ← Volver a Ligas
        </a>

    </div>

    <hr>

    {{-- ===================== GRUPOS ===================== --}}

    <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
        <h5>Grupos</h5>

        <a href="{{ route('admin.groups.create', $league) }}" class="btn btn-sm btn-primary">
            + Crear Grupo
        </a>
    </div>

    @if($league->groups->count())

        <ul class="list-group mb-4">

            @foreach($league->groups as $group)

                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <div>
                        <strong>{{ $group->name }}</strong>
                        <span class="badge bg-secondary ms-2">
                            {{ $group->teams->count() }} equipos
                        </span>
                    </div>

                    <div>
                        <a href="{{ route('admin.groups.edit', [$league, $group]) }}" class="btn btn-sm btn-warning">
                            Editar
                        </a>

                        <form action="{{ route('admin.groups.destroy', [$league, $group]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar grupo?')">
                                Eliminar
                            </button>
                        </form>
                    </div>

                </li>

            @endforeach

        </ul>

    @else
        <p class="text-muted">No hay grupos aún.</p>
    @endif



    {{-- ===================== EQUIPOS ===================== --}}

    <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
        <h5>Equipos</h5>

        <a href="{{ route('admin.teams.create', $league) }}" class="btn btn-sm btn-success">
            + Crear Equipo
        </a>
    </div>

    @if($league->teams->count())

        <ul class="list-group">

            @foreach($league->teams as $team)

                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center">

                        @if($team->image)
                            <img src="{{ asset('storage/' . $team->image) }}" width="45" height="45"
                                style="object-fit:cover;border-radius:10px;margin-right:15px;">
                        @else
                            <div style="width:45px;height:45px;
                                                        background:#374151;
                                                        border-radius:10px;
                                                        margin-right:15px;">
                            </div>
                        @endif

                        <div>
                            <strong>{{ $team->name }}</strong>

                            <small class="text-muted ms-2">
                                ({{ $team->players->count() }} jugadores)
                            </small>

                            @if($team->group)
                                <span class="badge bg-info ms-2">
                                    {{ $team->group->name }}
                                </span>
                            @endif
                        </div>

                    </div>

                    <div>
                        <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="btn btn-sm btn-info">
                            Ver
                        </a>

                        <a href="{{ route('admin.teams.edit', [$league, $team]) }}" class="btn btn-sm btn-warning">
                            Editar
                        </a>

                        <form action="{{ route('admin.teams.destroy', [$league, $team]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar equipo?')">
                                Eliminar
                            </button>
                        </form>
                    </div>

                </li>

            @endforeach

        </ul>

    @else
        <p class="text-muted">No hay equipos aún.</p>
    @endif

@endsection