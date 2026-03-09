<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Enums\GameStatus;
use Carbon\Carbon;

class ScorekeeperDashboardController extends Controller
{
    public function index()
    {
        // Obtener juegos que están programados (scheduled) o en vivo (live)
        // Preferiblemente de la fecha actual o recientes.
        $games = Game::with(['homeTeam', 'awayTeam', 'league', 'tournament'])
            ->whereIn('status', [GameStatus::SCHEDULED, GameStatus::LIVE])
            ->orderBy('game_date', 'asc')
            ->orderBy('status', 'desc') // Live primero si hay varios
            ->get();

        return view('admin.scorekeeper.dashboard', compact('games'));
    }
}
