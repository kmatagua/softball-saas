@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3>Crear Equipo</h3>
            <small class="text-muted">
                Liga: {{ $league->name }}
            </small>
        </div>

        <a href="{{ route('admin.leagues.show', $league) }}" class="btn btn-outline-light btn-sm">
            ← Volver a Liga
        </a>
    </div>

    <hr>

    <form method="POST" action="{{ route('admin.teams.store', $league) }}" enctype="multipart/form-data">
        @csrf

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre del Equipo</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- Grupo --}}
        @if($groups->count())
            <div class="mb-3">
                <label class="form-label">Grupo</label>
                <select name="group_id" class="form-control">
                    <option value="">Sin grupo</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">
                            {{ $group->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- Imagen --}}
        <div class="mb-3">
            <label class="form-label">Logo del Equipo</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">
                JPG o PNG - Máximo 2MB
            </small>
        </div>

        <button type="submit" class="btn btn-success">
            Crear Equipo
        </button>

    </form>

@endsection