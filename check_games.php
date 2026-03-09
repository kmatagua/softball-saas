<?php
$games = \App\Models\Game::all();
$live = \App\Models\Game::where('status', \App\Enums\GameStatus::LIVE)->get();
echo 'Total Games: ' . $games->count() . "\n";
echo 'Live Games: ' . $live->count() . "\n";
