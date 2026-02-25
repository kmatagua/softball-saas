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

{{-- ===================== TORNEOS ===================== --}}

<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h5>Torneos</h5>

    <a href="{{ route('admin.tournaments.index', $league) }}" class="btn btn-sm btn-dark">
        Gestionar Torneos
    </a>
</div>

@if($league->tournaments->count())

    @foreach($league->tournaments as $tournament)

        <div class="card mb-4">

            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $tournament->name }}</strong>

                    <span class="badge bg-secondary ms-2">
                        {{ $tournament->groups_count }} grupos
                    </span>

                    <span class="badge bg-info ms-2">
                        Clasifican {{ $tournament->qualifies_per_group }}
                    </span>
                </div>

                <div>
                    <a href="{{ route('admin.teams.create', [$league, $tournament]) }}"
                       class="btn btn-sm btn-success">
                        + Equipo
                    </a>

                    <a href="{{ route('admin.games.index', [$league, $tournament]) }}"
                       class="btn btn-sm btn-primary">
                        Juegos
                    </a>

                    <a href="{{ route('admin.games.create', [$league, $tournament]) }}"
                       class="btn btn-sm btn-warning">
                        + Juego
                    </a>

                    <a href="{{ route('admin.tournaments.standings', [$league, $tournament]) }}"
                       class="btn btn-sm btn-info">
                        Tabla
                    </a>
                </div>
            </div>

            <div class="card-body">

                @if($tournament->groups->count())

                    <ul class="list-group">

                        @foreach($tournament->groups as $group)

                            <li class="list-group-item d-flex justify-content-between align-items-center">

                                <div>
                                    <strong>{{ $group->name }}</strong>

                                    <span class="badge bg-secondary ms-2">
                                        {{ $group->teams->count() }} equipos
                                    </span>
                                </div>

                            </li>

                        @endforeach

                    </ul>

                @else
                    <p class="text-muted mb-0">
                        No hay grupos en este torneo.
                    </p>
                @endif

            </div>

        </div>

    @endforeach

@else
    <p class="text-muted">No hay torneos creados aún.</p>
@endif

@endsection