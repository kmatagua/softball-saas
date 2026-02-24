<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamWebController extends Controller
{
    public function create(League $league, Tournament $tournament)
    {
        $groups = $tournament->groups;
        return view('admin.teams.create', compact('league', 'tournament', 'groups'));
    }

    public function store(Request $request, League $league, Tournament $tournament)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'nullable|exists:groups,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'group_id' => $request->group_id,
            'image' => null
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('teams', 'public');
        }

        $tournament->teams()->create([
            'name' => $request->name,
            'group_id' => $request->group_id,
            'league_id' => $league->id,
            'tournament_id' => $tournament->id,
            'image' => $data['image']
        ]);

        return redirect()
            ->route('admin.leagues.show', $league)
            ->with('success', 'Equipo creado.');
    }

    public function edit(League $league, Team $team)
    {
        $groups = $league->groups;
        return view('admin.teams.edit', compact('league', 'team', 'groups'));
    }

    public function update(Request $request, League $league, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'nullable|exists:groups,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'group_id' => $request->group_id,
        ];

        if ($request->hasFile('image')) {

            // eliminar imagen anterior
            if ($team->image) {
                Storage::disk('public')->delete($team->image);
            }

            $data['image'] = $request->file('image')
                ->store('teams', 'public');
        }

        $team->update($data);

        return redirect()
            ->route('admin.leagues.show', $league)
            ->with('success', 'Equipo actualizado.');
    }

    public function destroy(League $league, Team $team)
    {
        // eliminar imagen antes de borrar
        if ($team->image) {
            Storage::disk('public')->delete($team->image);
        }

        $team->delete();

        return redirect()
            ->route('admin.leagues.show', $league)
            ->with('success', 'Equipo eliminado.');
    }

    public function show(League $league, Team $team)
    {
        $team->load([
            'players' => function ($query) {
                $query->orderBy('jersey_number');
            }
        ]);

        return view('admin.teams.show', compact('league', 'team'));
    }
}