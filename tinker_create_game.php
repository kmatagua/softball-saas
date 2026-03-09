$league = \App\Models\League::first();
if (!$league) {
    echo "No hay ligas.\n";
    return;
}

$tournament = \App\Models\Tournament::where('league_id', $league->id)->first();
$group = \App\Models\Group::where('tournament_id', $tournament->id)->first();
$teams = \App\Models\Team::where('group_id', $group->id)->take(2)->get();

if ($teams->count() < 2) {
    echo "Faltan equipos para crear el juego. Se necesitan al menos 2 en el mismo grupo.\n";
    exit;
}

$game = \App\Models\Game::create([
    'league_id' => $league->id,
    'tournament_id' => $tournament->id,
    'group_id' => $group->id,
    'home_team_id' => $teams[0]->id,
    'away_team_id' => $teams[1]->id,
    'game_date' => now(),
    'location' => 'Estadio Central (Test)',
    'status' => \App\Enums\GameStatus::LIVE,
    'stage' => 'regular',
    'home_score' => 0,
    'away_score' => 0,
    'outs' => 0,
    'current_inning' => 1,
    'half_inning' => 'top',
]);

echo "Juego de prueba creado exitosamente: {$teams[0]->name} vs {$teams[1]->name}\n";
