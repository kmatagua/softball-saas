@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center">

            @if($player->image)
                <img src="{{ asset('storage/' . $player->image) }}" width="100" height="100"
                    style="object-fit:cover;border-radius:50%;margin-right:20px;">
            @endif

            <div>
                <h3>#{{ $player->jersey_number ?? '-' }}
                    {{ $player->first_name }} {{ $player->last_name }}
                </h3>
                <small class="text-muted">
                    Equipo: {{ $team->name }}
                </small>
            </div>

        </div>

        <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="btn btn-outline-light btn-sm">
            ← Volver al Equipo
        </a>

    </div>

    <hr>

    <div class="row text-center">

        <div class="col-md-2">
            <h4>{{ $gamesPlayed }}</h4>
            <small>Juegos</small>
        </div>

        <div class="col-md-2">
            <h4>{{ $atBats }}</h4>
            <small>Turnos</small>
        </div>

        <div class="col-md-2">
            <h4>{{ $hits }}</h4>
            <small>Hits</small>
        </div>

        <div class="col-md-2">
            <h4>{{ $runs }}</h4>
            <small>Carreras</small>
        </div>

        <div class="col-md-2">
            <h4>{{ $rbi }}</h4>
            <small>RBI</small>
        </div>

        <div class="col-md-2">
            <h4>{{ $average }}</h4>
            <small>AVG</small>
        </div>

    </div>

@endsection