@extends('layouts.app')

@section('content')

<h3>{{ $league->name }}</h3>

<hr>

<h5>Grupos</h5>
<ul>
@foreach($league->groups as $group)
    <li>{{ $group->name }}</li>
@endforeach
</ul>

<h5 class="mt-4">Equipos</h5>
<ul>
@foreach($league->teams as $team)
    <li>{{ $team->name }} ({{ $team->players->count() }} jugadores)</li>
@endforeach
</ul>

@endsection