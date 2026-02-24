<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentWebController extends Controller
{
    public function index(League $league)
    {
        $tournaments = $league->tournaments;

        return view('admin.tournaments.index', compact('league', 'tournaments'));
    }

    public function create(League $league)
    {
        return view('admin.tournaments.create', compact('league'));
    }

    public function store(Request $request, League $league)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'qualifies_per_group' => 'required|integer|min:1',
            'groups_count' => 'required|integer|min:1'
        ]);

        $tournament = $league->tournaments()->create([
            'name' => $request->name,
            'qualifies_per_group' => $request->qualifies_per_group,
            'groups_count' => $request->groups_count,
            'is_active' => true
        ]);

        // Crear grupos automáticamente
        for ($i = 0; $i < $tournament->groups_count; $i++) {

            $groupName = 'Grupo ' . chr(65 + $i); // 65 = A

            $tournament->groups()->create([
                'name' => $groupName,
                'league_id' => $league->id
            ]);
        }

        return redirect()
            ->route('admin.tournaments.index', $league)
            ->with('success', 'Torneo creado correctamente.');
    }

    public function destroy(League $league, Tournament $tournament)
    {
        $tournament->delete();

        return redirect()
            ->route('admin.tournaments.index', $league)
            ->with('success', 'Torneo eliminado.');
    }

    public function standings(League $league, Tournament $tournament)
    {
        $standings = $tournament->standingsByGroup();

        return view('admin.tournaments.standings',
            compact('league', 'tournament', 'standings'));
    }
}