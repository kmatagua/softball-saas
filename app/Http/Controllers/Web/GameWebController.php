<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Game;
use App\Models\League;
use App\Models\Tournament;

class GameWebController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | ======================= PÚBLICO =======================
     |--------------------------------------------------------------------------
     */

    public function show($slug, $id)
    {
        $league = League::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $game = Game::with(['homeTeam', 'awayTeam'])
            ->where('league_id', $league->id)
            ->findOrFail($id);

        return view('leagues.games.show', compact('league', 'game'));
    }

    public function scoreboard($slug, $id)
    {
        $league = League::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $game = Game::with([
            'homeTeam.players',
            'awayTeam.players'
        ])
            ->where('league_id', $league->id)
            ->findOrFail($id);

        return view('leagues.games.scoreboard', compact('league', 'game'));
    }

    public function lineup($slug, $id)
    {
        $league = League::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $game = Game::with([
            'homeTeam.players',
            'awayTeam.players',
            'lineups.player'
        ])
            ->where('league_id', $league->id)
            ->findOrFail($id);

        return view('leagues.games.lineup', compact('league', 'game'));
    }

    public function scorekeeperLineup($slug, $id)
    {
        $league = League::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $game = Game::with([
            'homeTeam.players',
            'awayTeam.players',
            'lineups.player'
        ])
            ->where('league_id', $league->id)
            ->findOrFail($id);

        return view('leagues.games.lineup_scorekeeper', compact('league', 'game'));
    }

    /*
     |--------------------------------------------------------------------------
     | ======================= ADMIN =======================
     |--------------------------------------------------------------------------
     */

    public function leagueIndex(League $league)
    {
        $games = Game::where('league_id', $league->id)
            ->with(['homeTeam', 'awayTeam', 'tournament'])
            ->latest()
            ->get();

        return view('admin.games.league_index', compact('league', 'games'));
    }

    public function adminIndex(League $league, Tournament $tournament)
    {
        $games = $tournament->games()
            ->with(['homeTeam', 'awayTeam'])
            ->latest()
            ->get();

        return view('admin.games.index', compact('league', 'tournament', 'games'));
    }

    public function create(League $league, Tournament $tournament)
    {
        $groups = $tournament->groups;

        $selectedGroupId = request('group_id');
        $teams = collect();

        if ($selectedGroupId) {
            $teams = Team::where('tournament_id', $tournament->id)
                ->where('group_id', $selectedGroupId)
                ->get();
        }

        return view('admin.games.create', compact(
            'league',
            'tournament',
            'groups',
            'teams',
            'selectedGroupId'
        ));
    }

    public function store(Request $request, League $league, Tournament $tournament)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id',
            'stage' => 'required',
            'game_date' => 'nullable|date',
            'location' => 'nullable|string|max:255'
        ]);

        $homeTeam = Team::findOrFail($request->home_team_id);
        $awayTeam = Team::findOrFail($request->away_team_id);

        // Validar que ambos equipos pertenezcan al torneo
        if (
        $homeTeam->tournament_id !== $tournament->id ||
        $awayTeam->tournament_id !== $tournament->id
        ) {
            return back()
                ->withErrors('Los equipos no pertenecen a este torneo.')
                ->withInput();
        }

        // Si es fase regular, deben ser del mismo grupo
        if ($request->stage === 'regular') {

            if ($homeTeam->group_id !== $awayTeam->group_id) {
                return back()
                    ->withErrors('En fase regular ambos equipos deben pertenecer al mismo grupo.')
                    ->withInput();
            }
        }

        $tournament->games()->create([
            'league_id' => $league->id,
            'tournament_id' => $tournament->id,
            'group_id' => $request->group_id,
            'home_team_id' => $request->home_team_id,
            'away_team_id' => $request->away_team_id,
            'stage' => $request->stage,
            'game_date' => $request->game_date,
            'location' => $request->location,
            'status' => 'scheduled'
        ]);

        return redirect()
            ->route('admin.games.index', [$league, $tournament])
            ->with('success', 'Juego creado correctamente.');
    }

    public function adminShow(League $league, Tournament $tournament, Game $game)
    {
        return view('admin.games.show',
            compact('league', 'tournament', 'game'));
    }

    public function edit(League $league, Tournament $tournament, Game $game)
    {
        return view('admin.games.edit',
            compact('league', 'tournament', 'game'));
    }

    public function update(Request $request, League $league, Tournament $tournament, Game $game)
    {
        $request->validate([
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'status' => 'required',
            'game_date' => 'nullable|date',
            'location' => 'nullable|string|max:255'
        ]);

        $game->update([
            'home_score' => $request->home_score,
            'away_score' => $request->away_score,
            'status' => $request->status,
            'game_date' => $request->game_date,
            'location' => $request->location
        ]);

        return redirect()
            ->route('admin.games.index', [$league, $tournament])
            ->with('success', 'Juego actualizado.');
    }

    public function destroy(League $league, Tournament $tournament, Game $game)
    {
        $game->delete();

        return redirect()
            ->route('admin.games.index', [$league, $tournament])
            ->with('success', 'Juego eliminado.');
    }

    public function scorekeeper(League $league, Tournament $tournament, Game $game)
    {
        // El juego debe cargar primero toda su asociación de equipos
        $game->load(['homeTeam', 'awayTeam']);
        return view('admin.scorekeeper.index', compact('league', 'tournament', 'game'));
    }
}