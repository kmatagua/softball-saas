<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameEvent;
use App\Models\Team;
use App\Enums\GameStatus;
use App\Events\GameEventCreated;

class GameEventController extends Controller
{
    public function store(Request $request)
    {
        // Validar request
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'team_id' => 'required|exists:teams,id',
            'inning' => 'required|integer|min:1|max:20',
            'event_type' => 'required|string|max:50',
            'runs' => 'required|integer|min:0|max:4'
        ]);

        // Buscar juego
        $game = Game::findOrFail($validated['game_id']);

        // Validar que el juego esté en vivo
        if ($game->status !== GameStatus::LIVE) {
            return response()->json([
                'message' => 'Solo puedes registrar eventos en juegos en vivo.'
            ], 422);
        }

        // Validar que el equipo pertenezca al juego
        $teamId = $validated['team_id'];

        if ($teamId == $game->home_team_id) {

            $game->home_score += $validated['runs'];

        } elseif ($teamId == $game->away_team_id) {

            $game->away_score += $validated['runs'];

        } else {

            return response()->json([
                'message' => 'El equipo no pertenece a este juego.'
            ], 422);

        }

        // Guardar evento
        $event = GameEvent::create([
            'game_id' => $validated['game_id'],
            'team_id' => $validated['team_id'],
            'inning' => $validated['inning'],
            'event_type' => $validated['event_type'],
            'runs' => $validated['runs']
        ]);

        // Guardar marcador actualizado
        $game->save();

        // Broadcast en tiempo real
        broadcast(new GameEventCreated($event));

        // Respuesta completa
        return response()->json([
            'message' => 'Evento creado correctamente',
            'event' => $event,
            'score' => [
                'home_score' => $game->home_score,
                'away_score' => $game->away_score
            ]
        ], 201);
    }
}