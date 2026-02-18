@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3>Editar Jugador</h3>
            <small class="text-muted">
                Equipo: {{ $team->name }}
            </small>
        </div>

        <a href="{{ route('admin.teams.show', [$league, $team]) }}" class="btn btn-outline-light btn-sm">
            ← Volver al Equipo
        </a>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <hr>

    <form method="POST" action="{{ route('admin.players.update', [$league, $team, $player]) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nombres --}}
        <div class="mb-3">
            <label class="form-label">Nombres</label>
            <input type="text" name="first_name" value="{{ old('first_name', $player->first_name) }}" class="form-control"
                required>
        </div>

        {{-- Apellidos --}}
        <div class="mb-3">
            <label class="form-label">Apellidos</label>
            <input type="text" name="last_name" value="{{ old('last_name', $player->last_name) }}" class="form-control"
                required>
        </div>

        {{-- DNI --}}
        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" value="{{ old('dni', $player->dni) }}" class="form-control">
        </div>

        {{-- Número --}}
        <div class="mb-3">
            <label class="form-label">Número de Camiseta</label>
            <input type="number" name="jersey_number" value="{{ old('jersey_number', $player->jersey_number) }}"
                class="form-control">
        </div>

        {{-- Imagen actual --}}
        @if($player->image)
            <div class="mb-3">
                <label class="form-label">Foto Actual</label>
                <div>
                    <img src="{{ asset('storage/' . $player->image) }}" width="100" height="100"
                        style="object-fit:cover;border-radius:50%;">
                </div>
            </div>
        @endif

        {{-- Nueva imagen --}}
        <div class="mb-3">
            <label class="form-label">
                {{ $player->image ? 'Reemplazar Foto' : 'Foto del Jugador' }}
            </label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">
            Actualizar Jugador
        </button>

    </form>

@endsection