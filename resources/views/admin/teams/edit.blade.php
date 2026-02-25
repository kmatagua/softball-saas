@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3>Editar Equipo</h3>
            <small class="text-muted">
                Liga: {{ $league->name }}
            </small>
        </div>

        <a href="{{ route('admin.leagues.show', $league) }}" class="btn btn-outline-light btn-sm">
            < Volver a Liga
        </a>
    </div>

    <hr>

    <form method="POST" action="{{ route('admin.teams.update', [$league, $team]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre del Equipo</label>
            <input type="text" name="name" value="{{ old('name', $team->name) }}" class="form-control" required>
        </div>

        {{-- Grupo --}}
        @if($groups->count())
            <div class="mb-3">
                <label class="form-label">Grupo</label>
                <select name="group_id" class="form-control">
                    <option value="">Sin grupo</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ $team->group_id == $group->id ? 'selected' : '' }}>
                            {{ $group->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- Imagen actual --}}
        @if($team->image)
            <div class="mb-3">
                <label class="form-label">Logo Actual</label>
                <div>
                    <img src="{{ asset('storage/' . $team->image) }}" width="100" style="object-fit:cover;border-radius:12px;">
                </div>
            </div>
        @endif

        {{-- Nueva imagen --}}
        <div class="mb-3">
            <label class="form-label">
                {{ $team->image ? 'Reemplazar Logo' : 'Logo del Equipo' }}
            </label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">
            Actualizar Equipo
        </button>

    </form>

@endsection