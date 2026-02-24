@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Crear Juego - {{ $tournament->name }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Selector de Grupo --}}
    <form method="GET"
          action="{{ route('admin.games.create', [$league, $tournament]) }}"
          class="mb-3">

        <label>Seleccionar Grupo</label>
        <select name="group_id"
                class="form-select"
                onchange="this.form.submit()">

            <option value="">-- Seleccionar Grupo --</option>

            @foreach($groups as $group)
                <option value="{{ $group->id }}"
                    {{ $selectedGroupId == $group->id ? 'selected' : '' }}>
                    {{ $group->name }}
                </option>
            @endforeach
        </select>
    </form>

    <form method="POST"
          action="{{ route('admin.games.store', [$league, $tournament]) }}">

        @csrf

        <input type="hidden" name="group_id" value="{{ $selectedGroupId }}">

        <div class="mb-3">
            <label>Fase</label>
            <select name="stage" class="form-select" required>
                <option value="regular">Regular</option>
                <option value="quarterfinal">Cuartos</option>
                <option value="semifinal">Semifinal</option>
                <option value="final">Final</option>
            </select>
        </div>

        @if($selectedGroupId)

            <div class="mb-3">
                <label>Equipo Local</label>
                <select name="home_team_id" class="form-select" required>
                    <option value="">-- Seleccionar --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Equipo Visitante</label>
                <select name="away_team_id" class="form-select" required>
                    <option value="">-- Seleccionar --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}">
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        @else
            <p class="text-muted">
                Primero selecciona un grupo.
            </p>
        @endif

        <div class="mb-3">
            <label>Fecha</label>
            <input type="date"
                   name="game_date"
                   class="form-control">
        </div>

        <button class="btn btn-success">
            Guardar Juego
        </button>

    </form>

</div>

@endsection