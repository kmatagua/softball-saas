@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="mb-0">Juegos</h3>
            <small class="text-muted">
                {{ $league->name }} - {{ $tournament->name ?? '' }}
            </small>
        </div>

        <div>
            <a href="{{ route('admin.leagues.show', $league) }}"
               class="btn btn-sm btn-secondary">
                ← Volver a Liga
            </a>

            @isset($tournament)
                <a href="{{ route('admin.games.create', [$league, $tournament]) }}"
                   class="btn btn-sm btn-primary">
                    + Crear Juego
                </a>
            @endisset
        </div>

    </div>

    @if($games->count())

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Local</th>
                    <th>Visitante</th>
                    <th>Marcador</th>
                    <th>Etapa</th>
                    <th>Estado</th>
                    <th width="180">Acciones</th>
                </tr>
            </thead>
            <tbody>

                @foreach($games as $game)

                    <tr>
                        <td>{{ $game->game_date }}</td>

                        <td>{{ $game->homeTeam->name }}</td>

                        <td>{{ $game->awayTeam->name }}</td>

                        <td>
                            @if($game->status === 'finished')
                                <strong>
                                    {{ $game->home_score }} - {{ $game->away_score }}
                                </strong>
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ ucfirst($game->stage->value) }}
                        </td>

                        <td>
                            @if($game->status === 'finished')
                                <span class="badge bg-success">Finalizado</span>
                            @elseif($game->status === 'live')
                                <span class="badge bg-warning">En Vivo</span>
                            @else
                                <span class="badge bg-secondary">Programado</span>
                            @endif
                        </td>

                        <td>

                            <a href="{{ route('admin.games.show', [$league, $tournament, $game]) }}"
                               class="btn btn-sm btn-info">
                                Ver
                            </a>

                            <a href="{{ route('admin.games.edit', [$league, $tournament, $game]) }}"
                               class="btn btn-sm btn-warning">
                                Editar
                            </a>

                            <form action="{{ route('admin.games.destroy', [$league, $tournament, $game]) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar juego?')">
                                    Eliminar
                                </button>
                            </form>

                        </td>

                    </tr>

                @endforeach

            </tbody>
        </table>

    @else

        <div class="alert alert-info">
            No hay juegos registrados aún.
        </div>

    @endif

</div>

@endsection