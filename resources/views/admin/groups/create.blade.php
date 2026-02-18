@extends('layouts.app')

@section('content')

<h3>Crear Grupo - {{ $league->name }}</h3>

<form method="POST" action="{{ route('admin.groups.store', $league) }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nombre del Grupo</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">
        Guardar
    </button>

    <a href="{{ route('admin.leagues.show', $league) }}" class="btn btn-secondary">
        Cancelar
    </a>
</form>

@endsection