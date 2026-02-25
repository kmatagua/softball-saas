@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3>Detalle del Juego</h3>
            <small class="text-muted">
                {{ $league->name }} - {{ $tournament->name }}
            </small>
        </div>

        <a href="{{ route('admin.games.index', [$league, $tournament]) }}"
           class="btn btn-secondary btn-sm">
            ← Volver
        </a>
    </div>

    <div class="card">

        <div class="card-body">

            <h4 class="text-center mb-4">
                {{ $game->homeTeam->name }}
                vs
                {{ $game->awayTeam->name }}
            </h4>

            <div class="row text-center mb-3">

                <div class="col">
                    <h2>{{ $game->home_score ?? '-' }}</h2>
                </div>

                <div class="col">
                    <h2>{{ $game->away_score ?? '-' }}</h2>
                </div>

            </div>

            <hr>

            <p><strong>Fecha:</strong> {{ $game->game_date }}</p>
            <p><strong>Grupo:</strong> {{ $game->group->name ?? '-' }}</p>
            <p><strong>Etapa:</strong> {{ ucfirst($game->stage->value) }}</p>

            <p>
                <strong>Estado:</strong>
                @if($game->status === 'finished')
                    <span class="badge bg-success">Finalizado</span>
                @elseif($game->status === 'live')
                    <span class="badge bg-warning">En Vivo</span>
                @else
                    <span class="badge bg-secondary">Programado</span>
                @endif
            </p>

            <a href="{{ route('admin.games.edit', [$league, $tournament, $game]) }}"
               class="btn btn-warning">
                Editar Juego
            </a>

        </div>

    </div>

</div>

@endsection