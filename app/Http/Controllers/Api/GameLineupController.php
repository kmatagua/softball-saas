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
            'away_lineup' => 'required|array',
            'home_reserve' => 'nullable|array',
            'away_reserve' => 'nullable|array'
        ]);

        $game = Game::findOrFail($validated['game_id']);

        if ($game->status === GameStatus::LIVE) {
            return response()->json([
                'message' => 'No se puede modificar lineup inicial cuando el juego está en vivo.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Limpiar lineup anterior
            GameLineup::where('game_id', $game->id)->delete();

            $this->insertLineup($game, $validated['home_lineup'], $game->home_team_id, true);
            $this->insertLineup($game, $validated['away_lineup'], $game->away_team_id, true);

            if (!empty($validated['home_reserve'])) {
                $this->insertLineup($game, $validated['home_reserve'], $game->home_team_id, false);
            }
            if (!empty($validated['away_reserve'])) {
                $this->insertLineup($game, $validated['away_reserve'], $game->away_team_id, false);
            }

            DB::commit();
            return response()->json(['message' => 'Lineups y reservas guardados correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al guardar lineup.', 'error' => $e->getMessage()], 500);
        }
    }

    public function substitute(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'team_id' => 'required|exists:teams,id',
            'outgoing_player_id' => 'required|exists:players,id',
            'incoming_player_id' => 'required|exists:players,id',
            'field_position' => 'required|string'
        ]);

        $game = Game::findOrFail($validated['game_id']);
        
        $outgoing = GameLineup::where('game_id', $game->id)
            ->where('player_id', $validated['outgoing_player_id'])
            ->firstOrFail();

        $incoming = GameLineup::where('game_id', $game->id)
            ->where('player_id', $validated['incoming_player_id'])
            ->first();

        if (!$incoming || $incoming->batting_order !== null) {
            return response()->json(['message' => 'El jugador entrante debe estar en la reserva.'], 422);
        }

        DB::beginTransaction();
        try {
            // Si el jugador saliente está en alguna base, actualizarlo al entrante
            $bases = ['first_base_player_id', 'second_base_player_id', 'third_base_player_id'];
            foreach ($bases as $baseColumn) {
                if ($game->{$baseColumn} == $validated['outgoing_player_id']) {
                    $game->update([$baseColumn => $validated['incoming_player_id']]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Sustitución realizada con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error en la sustitución.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateReserve(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'required|exists:players,id',
            'action' => 'required|in:add,remove'
        ]);

        $game = Game::findOrFail($validated['game_id']);

        // Regla del Inning 3: Solo se puede modificar reserva hasta el final del inning 3
        if ($game->current_inning > 3) {
            return response()->json(['message' => 'La reserva está bloqueada después del Inning 3.'], 422);
        }

        if ($validated['action'] === 'add') {
            $exists = GameLineup::where('game_id', $game->id)
                ->where('player_id', $validated['player_id'])
                ->exists();

            if ($exists) {
                return response()->json(['message' => 'El jugador ya está en el equipo del juego.'], 422);
            }

            GameLineup::create([
                'game_id' => $game->id,
                'team_id' => $validated['team_id'],
                'player_id' => $validated['player_id'],
                'batting_order' => null,
                'field_position' => 'BN', // Banca
                'is_starter' => false,
                'is_active' => true
            ]);

            return response()->json(['message' => 'Jugador añadido a la reserva.']);
        } else {
            GameLineup::where('game_id', $game->id)
                ->where('player_id', $validated['player_id'])
                ->whereNull('batting_order')
                ->delete();

            return response()->json(['message' => 'Jugador eliminado de la reserva.']);
        }
    }

    private function insertLineup(Game $game, array $players, int $teamId, bool $isLineup)
    {
        foreach ($players as $playerData) {
            GameLineup::create([
                'game_id' => $game->id,
                'team_id' => $teamId,
                'player_id' => $playerData['player_id'],
                'batting_order' => $isLineup ? $playerData['batting_order'] : null,
                'field_position' => $playerData['field_position'] ?? ($isLineup ? null : 'BN'),
                'is_starter' => $isLineup,
                'is_active' => true
            ]);
        }
    }
}