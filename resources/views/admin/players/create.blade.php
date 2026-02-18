@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3>Agregar Jugador</h3>
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

    <form method="POST" action="{{ route('admin.players.store', [$league, $team]) }}" enctype="multipart/form-data">
        @csrf

        {{-- Nombres --}}
        <div class="mb-3">
            <label class="form-label">Nombres</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
        </div>

        {{-- Apellidos --}}
        <div class="mb-3">
            <label class="form-label">Apellidos</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
        </div>

        {{-- DNI --}}
        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" value="{{ old('dni') }}" class="form-control">
        </div>

        {{-- Número --}}
        <div class="mb-3">
            <label class="form-label">Número de Camiseta</label>
            <input type="number" name="jersey_number" value="{{ old('jersey_number') }}" class="form-control">
        </div>

        {{-- Imagen --}}
        <div class="mb-3">
            <label class="form-label">Foto del Jugador</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">
                JPG o PNG - Máximo 2MB
            </small>
        </div>

        <button type="submit" class="btn btn-success">
            Guardar Jugador
        </button>

    </form>

@endsection