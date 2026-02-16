@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Ligas</h2>
        <a href="{{ route('admin.leagues.create') }}" class="btn btn-primary">
            + Nueva Liga
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card bg-dark border-secondary">
        <div class="card-body">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leagues as $league)
                        <tr>
                            <td>{{ $league->name }}</td>
                            <td>{{ $league->slug }}</td>
                            <td>
                                @if($league->active)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.leagues.show', $league) }}" class="btn btn-sm btn-info text-white">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($leagues->isEmpty())
                <p class="text-center text-muted my-3">No hay ligas registradas aún.</p>
            @endif
        </div>
    </div>

@endsection