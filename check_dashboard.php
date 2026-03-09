<?php
use App\Models\Game;
use App\Enums\GameStatus;

$games = Game::with(['homeTeam', 'awayTeam', 'league', 'tournament'])
    ->whereIn('status', [GameStatus::SCHEDULED, GameStatus::LIVE])
    ->get();

echo "Found " . $games->count() . " games for dashboard.\n";
foreach ($games as $game) {
    echo "ID: {$game->id}, Status: " . ($game->status instanceof GameStatus ? $game->status->value : $game->status) . ", Date: {$game->game_date}\n";
}
