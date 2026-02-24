@extends('layouts.app')

@section('content')
    <div class="container">

        <h2>Crear Torneo - {{ $league->name }}</h2>

        @if ($errors->any())
            <div style="color:red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.tournaments.store', $league) }}">
            @csrf

            <div>
                <label>Nombre del Torneo</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div>
                <label>Cantidad de Grupos</label>
                <input type="number" name="groups_count" value="{{ old('groups_count', 2) }}" min="1" required>
            </div>

            <div>
                <label>Clasifican por Grupo</label>
                <input type="number" name="qualifies_per_group" value="{{ old('qualifies_per_group', 4) }}" min="1"
                    required>
            </div>

            <br>

            <button type="submit">Crear Torneo</button>

        </form>

    </div>
@endsection