@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center">

            @if($team->image)
                <img src="{{ asset('storage/' . $team->image) }}" width="90" height="90"
                    style="object-fit:cover;border-radius:15px;margin-right:20px;">
            @else
                <div style="width:90px;height:90px;
                                                background:#374151;
                                                border-radius:15px;
                                                margin-right:20px;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                font-size:32px;">
                    
                </div>
            @endif

            <div>
                <h3 class="mb-0">{{ $team->name }}</h3>
                <small class="text-muted">
                    Liga: {{ $league->name }}
                </small>
            </div>

        </div>

        <a href="{{ route('admin.leagues.show', $league) }}" class="btn btn-outline-light btn-sm">
            < Volver a Liga
        </a>

    </div>

    <hr>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Jugadores</h5>

        <a href="{{ route('admin.players.create', [$league, $team]) }}" class="btn btn-sm btn-success">
            + Agregar Jugador
        </a>
    </div>

    @if($team->players->count())

        <ul class="list-group">

            @foreach($team->players as $player)

                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center">

                        @if($player->image)
                            <img src="{{ asset('storage/' . $player->image) }}" width="50" height="50"
                                style="object-fit:cover;border-radius:50%;margin-right:15px;">
                        @else
                            <div style="width:50px;height:50px;
                                                                        background:#374151;
                                                                        border-radius:50%;
                                                                        margin-right:15px;">
                            </div>
                        @endif

                        <div>
                            <strong>#{{ $player->jersey_number ?? '-' }}</strong>
                            {{ $player->first_name }} {{ $player->last_name }}

                            @if($player->dni)
                                <small class="text-muted ms-2">
                                    DNI o CE: {{ $player->dni }}
                                </small>
                            @endif
                        </div>

                    </div>

                    <div>
                        <a href="{{ route('admin.players.show', [$league, $team, $player]) }}" class="btn btn-sm btn-info">
                            Ver
                        </a>
                        <a href="{{ route('admin.players.edit', [$league, $team, $player]) }}" class="btn btn-sm btn-warning">
                            Editar
                        </a>

                        <form action="{{ route('admin.players.destroy', [$league, $team, $player]) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar jugador?')">
                                Eliminar
                            </button>
                        </form>
                    </div>

                </li>

            @endforeach

        </ul>

    @else
        <p class="text-muted">Este equipo aún no tiene jugadores.</p>
    @endif

@endsection