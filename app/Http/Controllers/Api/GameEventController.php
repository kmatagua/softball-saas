<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameEvent;
use App\Models\Player;
use App\Enums\GameStatus;
use App\Events\GameEventCreated;

class GameEventController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id'   => 'required|exists:games,id',
            'team_id'   => 'required|exists:teams,id',
            'player_id' => 'required|exists:players,id',
            'event_type'=> 'required|string|max:50'
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
            'game_id'   => $game->id,
            'team_id'   => $validated['team_id'],
            'player_id' => $player->id,
            'inning'    => $game->current_inning,
            'event_type'=> $validated['event_type'],
            'runs'      => 0
        ]);

        $this->processEvent($game, $player, $validated['event_type']);

        // Walk-off
        if (
            $game->current_inning >= 7 &&
            $game->half_inning === 'bottom' &&
            $game->home_score > $game->away_score
        ) {
            $game->status = GameStatus::FINISHED;
        }

        $game->save();

        broadcast(new GameEventCreated($event));

        return response()->json([
            'score' => [
                'home' => $game->home_score,
                'away' => $game->away_score
            ],
            'inning' => $game->current_inning,
            'half'   => $game->half_inning,
            'outs'   => $game->outs,
            'bases'  => [
                'first'  => $game->first_base_player_id,
                'second' => $game->second_base_player_id,
                'third'  => $game->third_base_player_id
            ],
            'status' => $game->status
        ], 201);
    }

    private function processEvent(Game $game, Player $batter, string $type)
    {
        switch ($type) {

            case 'single':
                $this->advanceRunners($game, $batter, 1);
                break;

            case 'double':
                $this->advanceRunners($game, $batter, 2);
                break;

            case 'triple':
                $this->advanceRunners($game, $batter, 3);
                break;

            case 'homerun':
                $this->advanceRunners($game, $batter, 4);
                break;

            case 'walk':
            case 'hbp':
                $this->handleWalk($game, $batter);
                break;

            case 'out':
                $this->handleOut($game);
                break;
        }
    }

    private function handleOut(Game $game)
    {
        $game->outs++;

        if ($game->outs >= 3) {

            $game->outs = 0;

            // limpiar bases
            $game->first_base_player_id = null;
            $game->second_base_player_id = null;
            $game->third_base_player_id = null;

            // cambiar mitad
            if ($game->half_inning === 'top') {
                $game->half_inning = 'bottom';
            } else {
                $game->half_inning = 'top';
                $game->current_inning++;
            }
        }
    }
    private function handleWalk(Game $game, Player $batter)
    {
        // Si bases llenas, anota carrera forzada
        if (
            $game->first_base_player_id &&
            $game->second_base_player_id &&
            $game->third_base_player_id
        ) {

            $this->scoreRun($game);

            GameEvent::create([
                'game_id' => $game->id,
                'team_id' => $batter->team_id,
                'player_id' => $batter->id,
                'inning' => $game->current_inning,
                'event_type' => 'run_scored',
                'runs' => 1,
                'rbi' => 0,
                'scored_player_id' => $game->third_base_player_id
            ]);

            $game->third_base_player_id = $game->second_base_player_id;
            $game->second_base_player_id = $game->first_base_player_id;
            $game->first_base_player_id = $batter->id;

            return;
        }

        // Avance simple forzado
        if (!$game->first_base_player_id) {
            $game->first_base_player_id = $batter->id;
            return;
        }

        if (!$game->second_base_player_id) {
            $game->second_base_player_id = $game->first_base_player_id;
            $game->first_base_player_id = $batter->id;
            return;
        }

        if (!$game->third_base_player_id) {
            $game->third_base_player_id = $game->second_base_player_id;
            $game->second_base_player_id = $game->first_base_player_id;
            $game->first_base_player_id = $batter->id;
            return;
        }
    }

    private function advanceRunners(Game $game, Player $batter, int $bases)
    {
        $runners = [
            3 => $game->third_base_player_id,
            2 => $game->second_base_player_id,
            1 => $game->first_base_player_id
        ];

        $newBases = [
            1 => null,
            2 => null,
            3 => null
        ];

        $rbiCount = 0;

        foreach ($runners as $base => $runnerId) {

            if (!$runnerId) continue;

            $newPosition = $base + $bases;

            if ($newPosition >= 4) {

                $this->scoreRun($game);
                $rbiCount++;

                // Registrar quién anotó
                GameEvent::create([
                    'game_id' => $game->id,
                    'team_id' => $batter->team_id,
                    'player_id' => $batter->id,
                    'inning' => $game->current_inning,
                    'event_type' => 'run_scored',
                    'runs' => 1,
                    'rbi' => 0,
                    'scored_player_id' => $runnerId
                ]);

            } else {
                $newBases[$newPosition] = $runnerId;
            }
        }

        // Bateador
        if ($bases >= 4) {

            $this->scoreRun($game);
            $rbiCount++;

            GameEvent::create([
                'game_id' => $game->id,
                'team_id' => $batter->team_id,
                'player_id' => $batter->id,
                'inning' => $game->current_inning,
                'event_type' => 'run_scored',
                'runs' => 1,
                'rbi' => 0,
                'scored_player_id' => $batter->id
            ]);

        } else {
            $newBases[$bases] = $batter->id;
        }

        // Actualizar RBI del evento principal
        GameEvent::where('game_id', $game->id)
            ->latest()
            ->first()
            ->update(['rbi' => $rbiCount]);

        $game->first_base_player_id = $newBases[1];
        $game->second_base_player_id = $newBases[2];
        $game->third_base_player_id = $newBases[3];
    }

    private function scoreRun(Game $game)
    {
        if ($game->half_inning === 'top') {
            $game->away_score++;
        } else {
            $game->home_score++;
        }
    }
}