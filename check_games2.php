<?php
use App\Models\Game;
$games = Game::all();
echo "Total Games: " . $games->count() . "\n";
foreach ($games as $game) {
    if ($game->status instanceof \App\Enums\GameStatus) {
        echo "Game {$game->id} Status: " . $game->status->value . "\n";
    }
    else {
        echo "Game {$game->id} Status: " . $game->status . "\n";
    }
}
