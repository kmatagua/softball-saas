<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
use App\Models\Team;
use App\Models\Player;
use App\Models\GameLineup;

$game = Game::find(146);
if (!$game) {
    die("Game 146 not found\n");
}

echo "Game 146: Team {$game->home_team_id} vs Team {$game->away_team_id}\n";

$teams = [$game->home_team_id, $game->away_team_id];

foreach ($teams as $teamId) {
    $team = Team::find($teamId);
    echo "Processing Team: {$team->name}\n";

    // Create or get 9 players
    for ($i = 1; $i <= 9; $i++) {
        $player = Player::firstOrCreate(
        ['team_id' => $teamId, 'first_name' => "Player $i", 'last_name' => "Team $teamId"],
        ['batting_average' => 0.250]
        );

        GameLineup::firstOrCreate(
        [
            'game_id' => $game->id,
            'team_id' => $teamId,
            'player_id' => $player->id
        ],
        [
            'batting_order' => $i,
            'field_position' => $i, // Simple 1-9 mapping
            'is_starter' => true,
            'is_active' => true
        ]
        );
    }
}

echo "Lineup seeded successfully for game 146\n";
