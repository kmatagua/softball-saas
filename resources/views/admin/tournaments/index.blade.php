@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Torneos - {{ $league->name }}</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.tournaments.create', $league) }}"
           class="btn btn-dark">
            + Crear Torneo
        </a>
    </div>

    @if($tournaments->isEmpty())
        <p>No hay torneos registrados.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Grupos</th>
                    <th>Clasifican</th>
                    <th>Activo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tournaments as $tournament)
                    <tr>
                        <td>{{ $tournament->name }}</td>
                        <td>{{ $tournament->groups_count }}</td>
                        <td>{{ $tournament->qualifies_per_group }}</td>
                        <td>{{ $tournament->is_active ? 'Sí' : 'No' }}</td>
                        <td>
                            <form action="{{ route('admin.tournaments.destroy', [$league, $tournament]) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar torneo?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>

@endsection