@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3>Editar Liga</h3>
            <small class="text-muted">
                Modifica los datos de la liga
            </small>
        </div>

        <a href="{{ route('admin.leagues.index') }}" class="btn btn-outline-light btn-sm">
            ← Volver
        </a>
    </div>

    <hr>

    <form method="POST" action="{{ route('admin.leagues.update', $league) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $league->name) }}" class="form-control" required>
        </div>

        {{-- Imagen actual --}}
        @if($league->image)
            <div class="mb-3">
                <label class="form-label">Logo Actual</label>
                <div>
                    <img src="{{ asset('storage/' . $league->image) }}" width="120"
                        style="object-fit:cover;border-radius:12px;">
                </div>
            </div>
        @endif

        {{-- Nueva imagen --}}
        <div class="mb-3">
            <label class="form-label">
                {{ $league->image ? 'Reemplazar Logo' : 'Logo de la Liga' }}
            </label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">
                JPG o PNG - Máximo 2MB
            </small>
        </div>

        <button type="submit" class="btn btn-primary">
            Actualizar Liga
        </button>

    </form>

@endsection