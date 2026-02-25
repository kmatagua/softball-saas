@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="mb-0">Tabla de Posiciones</h3>
            <small class="text-muted">
                {{ $league->name }} - {{ $tournament->name }}
            </small>
        </div>

        <div>
            <a href="{{ route('admin.leagues.show', $league) }}"
               class="btn btn-sm btn-secondary">
                < Volver a Liga
            </a>

            <a href="{{ route('admin.games.index', [$league, $tournament]) }}"
               class="btn btn-sm btn-primary">
                Ver Juegos
            </a>
        </div>

    </div>

    @foreach($standings as $groupName => $table)

        <h5 class="mt-4">{{ $groupName }}</h5>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Equipo</th>
                    <th>PJ</th>
                    <th>PG</th>
                    <th>PE</th>
                    <th>PP</th>
                    <th>CF</th>
                    <th>CC</th>
                    <th>DIF</th>
                    <th>PTS</th>
                </tr>
            </thead>
            <tbody>

                @foreach($table as $row)

                    @php
                        $rowClass = '';

                        if ($row['status'] === 'qualified') {
                            $rowClass = 'table-success';
                        } elseif ($row['status'] === 'eliminated') {
                            $rowClass = 'table-danger';
                        }
                    @endphp

                    <tr class="{{ $rowClass }}">
                        <td>{{ $row['position'] }}</td>
                        <td>{{ $row['team']->name }}</td>
                        <td>{{ $row['pj'] }}</td>
                        <td>{{ $row['pg'] }}</td>
                        <td>{{ $row['pe'] }}</td>
                        <td>{{ $row['pp'] }}</td>
                        <td>{{ $row['cf'] }}</td>
                        <td>{{ $row['cc'] }}</td>
                        <td>{{ $row['dif'] }}</td>
                        <td><strong>{{ $row['pts'] }}</strong></td>
                    </tr>

                @endforeach

            </tbody>
        </table>

    @endforeach

</div>

@endsection