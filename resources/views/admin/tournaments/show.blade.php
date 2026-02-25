@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3>{{ $tournament->name }}</h3>
            <small class="text-muted">
                {{ $league->name }}
            </small>
        </div>

        <a href="{{ route('admin.leagues.show', $league) }}"
           class="btn btn-secondary btn-sm">
            ← Volver a Liga
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">

            <p><strong>Grupos:</strong> {{ $tournament->groups_count }}</p>
            <p><strong>Clasifican por grupo:</strong> {{ $tournament->qualifies_per_group }}</p>
            <p><strong>Sistema de puntos:</strong>
                {{ $tournament->points_per_win }} -
                {{ $tournament->points_per_draw }} -
                {{ $tournament->points_per_loss }}
            </p>

            <hr>

            <a href="{{ route('admin.games.index', [$league, $tournament]) }}"
               class="btn btn-primary">
                Ver Juegos
            </a>

            <a href="{{ route('admin.tournaments.standings', [$league, $tournament]) }}"
               class="btn btn-info">
                Ver Tabla
            </a>

            <form method="POST"
                  action="{{ route('admin.tournaments.generatePlayoffs', [$league, $tournament]) }}"
                  style="display:inline;">
                @csrf
                <button class="btn btn-danger"
                        onclick="return confirm('¿Generar semifinales automáticamente?')">
                    Generar Playoffs
                </button>
            </form>

        </div>
    </div>

    <h5>Grupos</h5>

    @foreach($tournament->groups as $group)

        <div class="card mb-3">
            <div class="card-body">

                <strong>{{ $group->name }}</strong>

                <ul class="mt-2">
                    @foreach($group->teams as $team)
                        <li>{{ $team->name }}</li>
                    @endforeach
                </ul>

            </div>
        </div>

    @endforeach

</div>

@endsection