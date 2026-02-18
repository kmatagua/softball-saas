@extends('layouts.app')

@section('content')

<h3>Editar Grupo</h3>

<form method="POST" action="{{ route('admin.groups.update', [$league, $group]) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text"
               name="name"
               value="{{ old('name', $group->name) }}"
               class="form-control"
               required>
    </div>

    <button type="submit" class="btn btn-primary">
        Actualizar
    </button>

    <a href="{{ route('admin.leagues.show', $league) }}"
       class="btn btn-secondary">
        Cancelar
    </a>
</form>

@endsection