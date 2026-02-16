<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\League;

class GameWebController extends Controller
{
    public function index()
    {
        //dd('estoy entrando al controlador');
        $games = Game::with(['homeTeam','awayTeam'])
            ->where('status','live')
            ->get();
        //$games = Game::where('status','live')->get();
        return view('games.index', compact('games'));
    }

    public function show($slug, $id)
    {
        $league = League::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $game = Game::with(['homeTeam','awayTeam'])
            ->where('league_id', $league->id)
            ->findOrFail($id);

        return view('leagues.games.show', compact('league','game'));
    }
    public function scoreboard($slug, $id)
    {
        $league = \App\Models\League::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $game = \App\Models\Game::with([
            'homeTeam.players',
            'awayTeam.players'
        ])
        ->where('league_id', $league->id)
        ->findOrFail($id);

        return view('leagues.games.scoreboard', compact('league','game'));
    }
    public function lineup($slug, $id)
    {
        $league = \App\Models\League::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $game = \App\Models\Game::with([
            'homeTeam.players',
            'awayTeam.players',
            'lineups.player'
        ])
        ->where('league_id', $league->id)
        ->findOrFail($id);

        return view('leagues.games.lineup', compact('league','game'));
    }

    public function scorekeeperLineup($slug, $id)
    {
        $league = \App\Models\League::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $game = \App\Models\Game::with([
            'homeTeam.players',
            'awayTeam.players',
            'lineups.player'
        ])
        ->where('league_id', $league->id)
        ->findOrFail($id);

        return view('leagues.games.lineup_scorekeeper', compact('league','game'));
    }
}