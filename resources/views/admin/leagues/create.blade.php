@extends('layouts.app')

@section('content')

    <h3>Crear Liga</h3>

    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.leagues.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Logo de la Liga</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <button class="btn btn-warning">
            Crear
        </button>
    </form>

@endsection