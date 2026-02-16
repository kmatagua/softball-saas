<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use App\Models\Game;
use App\Models\Team;
use App\Enums\GameStage;
use App\Enums\GameStatus;

class GameController extends Controller
{
    public function index()
    {
        return Game::with(['homeTeam', 'awayTeam', 'league'])
            ->orderBy('game_date')
            ->get();
    }

    public function show($id)
    {
        $game = Game::with([
            'league',
            'homeTeam',
            'awayTeam',
            'events' => function ($query) {
                $query->orderBy('inning')
                    ->orderBy('created_at');
            },
            'events.team'
        ])->findOrFail($id);

        return response()->json([
            'game' => [
                'id' => $game->id,
                'status' => $game->status,
                'stage' => $game->stage,
                'game_date' => $game->game_date,

                'league' => [
                    'id' => $game->league->id,
                    'name' => $game->league->name
                ],

                'home_team' => [
                    'id' => $game->homeTeam->id,
                    'name' => $game->homeTeam->name,
                    'score' => $game->home_score
                ],

                'away_team' => [
                    'id' => $game->awayTeam->id,
                    'name' => $game->awayTeam->name,
                    'score' => $game->away_score
                ]
            ],

            'events' => $game->events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'team_id' => $event->team_id,
                    'team_name' => $event->team->name,
                    'inning' => $event->inning,
                    'event_type' => $event->event_type,
                    'runs' => $event->runs,
                    'created_at' => $event->created_at
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'league_id' => 'required|exists:leagues,id',
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'stage' => ['required', new Enum(GameStage::class)],
            'game_date' => 'nullable|date'
        ]);

        return Game::create([
            ...$validated,
            'status' => GameStatus::SCHEDULED,
            'home_score' => 0,
            'away_score' => 0
        ]);
    }

    public function start(Game $game)
    {
        if ($game->status !== GameStatus::SCHEDULED) {
            return response()->json([
                'message' => 'El juego no puede iniciarse.'
            ], 422);
        }

        $game->update([
            'status' => GameStatus::LIVE
        ]);

        return $game;
    }
}