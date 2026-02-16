<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        // El GlobalScope ya filtra automáticamente
        return Team::all();
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->league_id) {
            return response()->json([
                'message' => 'Este usuario no pertenece a ninguna liga'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $team = Team::create([
            'name' => $request->name,
            'league_id' => $user->league_id
        ]);

        return response()->json($team, 201);
    }
}