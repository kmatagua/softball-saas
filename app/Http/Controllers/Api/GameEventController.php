<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameEvent;
use App\Models\Player;
use App\Enums\GameStatus;
use App\Events\GameEventCreated;
use App\Actions\RecalculateGameState;

class GameEventController extends Controller
{
    protected RecalculateGameState $recalculator;

    public function __construct(RecalculateGameState $recalculator)
    {
        $this->recalculator = $recalculator;
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'required|exists:players,id',
            'pitcher_id' => 'nullable|exists:players,id',
            'event_type' => 'required|string|max:50'
        ]);

        $game = Game::findOrFail($validated['game_id']);

        if ($game->status !== GameStatus::LIVE) {
            return response()->json(['message' => 'Juego no está en vivo.'], 422);
        }

        // Validar turno correcto
        if (
        ($game->half_inning === 'top' && $validated['team_id'] != $game->away_team_id) ||
        ($game->half_inning === 'bottom' && $validated['team_id'] != $game->home_team_id)
        ) {
            return response()->json(['message' => 'No es el turno de ese equipo.'], 422);
        }

        $player = Player::findOrFail($validated['player_id']);

        if ($player->team_id != $validated['team_id']) {
            return response()->json(['message' => 'Jugador no pertenece al equipo.'], 422);
        }

        $event = GameEvent::create([
            'game_id' => $game->id,
            'team_id' => $validated['team_id'],
            'player_id' => $player->id,
            'pitcher_id' => $validated['pitcher_id'] ?? null,
            'inning' => $game->current_inning,
            'event_type' => $validated['event_type'],
            'runs' => 0
        ]);

        $this->recalculator->execute($game);

        $game->refresh();

        broadcast(new GameEventCreated($event));

        return response()->json([
            'score' => [
                'home' => $game->home_score,
                'away' => $game->away_score
            ],
            'inning' => $game->current_inning,
            'half' => $game->half_inning,
            'outs' => $game->outs,
            'bases' => $this->getDetailedBases($game),
            'status' => $game->status,
            'event' => $event->load('player', 'pitcher')
        ], 201);
    }

    public function update(Request $request, GameEvent $gameEvent)
    {
        $validated = $request->validate([
            'event_type' => 'required|string|max:50'
        ]);

        $game = Game::findOrFail($gameEvent->game_id);

        if ($game->status !== GameStatus::LIVE) {
            return response()->json(['message' => 'Juego no está en vivo.'], 422);
        }

        $gameEvent->update([
            'event_type' => $validated['event_type']
        ]);

        $this->recalculator->execute($game);
        $game->refresh();

        return response()->json([
            'message' => 'Evento actualizado',
            'game' => $game,
            'event' => $gameEvent
        ]);
    }

    public function destroy(GameEvent $gameEvent)
    {
        $game = Game::findOrFail($gameEvent->game_id);

        if ($game->status !== GameStatus::LIVE) {
            return response()->json(['message' => 'Juego no está en vivo.'], 422);
        }

        $gameEvent->delete();

        $this->recalculator->execute($game);
        $game->refresh();

        return response()->json([
            'message' => 'Evento eliminado',
            'game' => $game
        ]);
    }

    protected function getDetailedBases(Game $game)
    {
        return [
            'first' => $this->getPlayerData($game, $game->first_base_player_id),
            'second' => $this->getPlayerData($game, $game->second_base_player_id),
            'third' => $this->getPlayerData($game, $game->third_base_player_id),
        ];
    }

    protected function getPlayerData(Game $game, $playerId)
    {
        if (!$playerId) return null;

        $player = Player::find($playerId);
        if (!$player) return null;

        $lineup = $game->lineups()->where('player_id', $playerId)->first();
        
        return [
            'id' => $player->id,
            'first_name' => $player->first_name,
            'last_name' => $player->last_name,
            'position' => $lineup ? $lineup->field_position : null
        ];
    }
}