@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Crear Equipo - {{ $tournament->name }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.teams.store', [$league, $tournament]) }}"
          enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre del Equipo</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Grupo</label>
            <select name="group_id" class="form-select">
                <option value="">Sin grupo</option>

                @foreach($groups as $group)
                    <option value="{{ $group->id }}"
                        {{ old('group_id') == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen (opcional)</label>
            <input type="file"
                   name="image"
                   class="form-control">
        </div>

        <button type="submit" class="btn btn-success">
            Guardar Equipo
        </button>

        <a href="{{ route('admin.leagues.show', $league) }}"
           class="btn btn-secondary">
            Cancelar
        </a>

    </form>

</div>

@endsection