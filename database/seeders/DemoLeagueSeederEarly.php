<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\League;
use App\Models\Tournament;
use App\Models\Group;
use App\Models\Team;
use App\Models\Game;

class DemoLeagueSeederEarly extends Seeder
{
    public function run(): void
    {
        $league = League::create([
            'name' => 'Liga Demo Early',
            'slug' => 'liga-demo-early',
            'active' => true,
        ]);

        $tournament = Tournament::create([
            'league_id' => $league->id,
            'name' => 'Categoría Única',
            'groups_count' => 2,
            'qualifies_per_group' => 2,
            'points_per_win' => 3,
            'points_per_draw' => 1,
            'points_per_loss' => 0,
            'is_active' => true,
        ]);

        foreach (['Grupo A', 'Grupo B'] as $groupName) {

            $group = Group::create([
                'league_id' => $league->id,
                'tournament_id' => $tournament->id,
                'name' => $groupName,
            ]);

            $teams = [];

            for ($i = 1; $i <= 9; $i++) {
                $teams[] = Team::create([
                    'league_id' => $league->id,
                    'tournament_id' => $tournament->id,
                    'group_id' => $group->id,
                    'name' => "{$groupName} Equipo {$i}",
                ]);
            }

            // SOLO 3 jornadas
            $gamesCreated = 0;

            for ($i = 0; $i < count($teams) && $gamesCreated < 12; $i++) {
                for ($j = $i + 1; $j < count($teams) && $gamesCreated < 12; $j++) {

                    Game::create([
                        'league_id' => $league->id,
                        'tournament_id' => $tournament->id,
                        'group_id' => $group->id,
                        'home_team_id' => $teams[$i]->id,
                        'away_team_id' => $teams[$j]->id,
                        'stage' => 'regular',
                        'status' => 'finished',
                        'home_score' => rand(0, 8),
                        'away_score' => rand(0, 8),
                        'game_date' => now(),
                    ]);

                    $gamesCreated++;
                }
            }
        }
    }
}