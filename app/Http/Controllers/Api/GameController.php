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
            'events.team',
            'lineups.player'
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
                ],

                // --- Live State ---
                'current_inning' => $game->current_inning,
                'half_inning' => $game->half_inning,
                'outs' => $game->outs,
                'balls' => 0, // Por ahora el backend no trackea bolas y strikes completos por picheo
                'strikes' => 0,
                'bases' => [
                    'first' => $game->first_base_player_id,
                    'second' => $game->second_base_player_id,
                    'third' => $game->third_base_player_id
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
        }),

            'lineups' => $game->lineups->map(function ($lineup) {
            return [
                    'id' => $lineup->id,
                    'team_id' => $lineup->team_id,
                    'player_id' => $lineup->player_id,
                    'player_name' => $lineup->player->first_name . ' ' . $lineup->player->last_name,
                    'batting_order' => $lineup->batting_order,
                    'field_position' => $lineup->field_position,
                    'is_starter' => $lineup->is_starter
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