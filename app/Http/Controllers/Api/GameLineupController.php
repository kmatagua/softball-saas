<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Game;
use App\Models\GameLineup;
use App\Models\Player;
use App\Enums\GameStatus;

class GameLineupController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'home_lineup' => 'required|array',
            'away_lineup' => 'required|array'
        ]);

        $game = Game::findOrFail($validated['game_id']);

        if ($game->status === GameStatus::LIVE) {
            return response()->json([
                'message' => 'No se puede modificar lineup cuando el juego está en vivo.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            // Limpiar lineup anterior
            GameLineup::where('game_id', $game->id)->delete();

            $this->insertLineup($game, $validated['home_lineup'], $game->home_team_id);
            $this->insertLineup($game, $validated['away_lineup'], $game->away_team_id);

            DB::commit();

            return response()->json([
                'message' => 'Lineups guardados correctamente.'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Error al guardar lineup.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function insertLineup(Game $game, array $players, int $teamId)
    {
        $seenPlayers = [];

        foreach ($players as $playerData) {

            if (in_array($playerData['player_id'], $seenPlayers)) {
                throw new \Exception('Jugador duplicado en lineup.');
            }

            $seenPlayers[] = $playerData['player_id'];

            if (empty($playerData['field_position'])) {
                throw new \Exception('Todos los jugadores deben tener posición.');
            }

            $player = Player::findOrFail($playerData['player_id']);

            if ($player->team_id != $teamId) {
                throw new \Exception('Jugador no pertenece al equipo.');
            }

            GameLineup::create([
                'game_id' => $game->id,
                'team_id' => $teamId,
                'player_id' => $player->id,
                'batting_order' => $playerData['batting_order'],
                'field_position' => $playerData['field_position'],
                'is_starter' => true,
                'is_active' => true
            ]);
        }
    }
}