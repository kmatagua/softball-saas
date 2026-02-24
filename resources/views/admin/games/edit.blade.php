@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Editar Juego</h3>

    <form method="POST"
          action="{{ route('admin.games.update', [$league, $tournament, $game]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Marcador Local</label>
            <input type="number" name="home_score"
                   class="form-control"
                   value="{{ $game->home_score }}">
        </div>

        <div class="mb-3">
            <label>Marcador Visitante</label>
            <input type="number" name="away_score"
                   class="form-control"
                   value="{{ $game->away_score }}">
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select name="status" class="form-control">
                <option value="scheduled"
                    {{ $game->status == 'scheduled' ? 'selected' : '' }}>
                    Programado
                </option>
                <option value="finished"
                    {{ $game->status == 'finished' ? 'selected' : '' }}>
                    Finalizado
                </option>
            </select>
        </div>

        <button class="btn btn-primary">Guardar</button>

        <a href="{{ route('admin.games.index', [$league, $tournament]) }}"
           class="btn btn-secondary">
            Cancelar
        </a>

    </form>

</div>

@endsection