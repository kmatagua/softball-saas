<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Game;
use App\Models\Team;
use App\Models\Player;
use App\Models\League;
use App\Models\User;
use App\Enums\GameStatus;
use App\Enums\GameStage;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Database\Seeders\RolesSeeder;

class FullGameIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_game_lifecycle()
    {
        // 0. Auth & Roles
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Scorekeeper');
        Sanctum::actingAs($user);

        // 1. Setup
        $league = League::create(['name' => 'Test League', 'slug' => 'test-league']);
        $homeTeam = Team::create(['name' => 'Home', 'league_id' => $league->id]);
        $awayTeam = Team::create(['name' => 'Away', 'league_id' => $league->id]);

        $homePlayers = [];
        for ($i = 0; $i < 12; $i++) {
            $homePlayers[] = Player::create(['team_id' => $homeTeam->id, 'first_name' => 'H', 'last_name' => "P$i"]);
        }
        $awayPlayers = [];
        for ($i = 0; $i < 12; $i++) {
            $awayPlayers[] = Player::create(['team_id' => $awayTeam->id, 'first_name' => 'A', 'last_name' => "P$i"]);
        }

        $game = Game::create([
            'league_id' => $league->id,
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'status' => GameStatus::SCHEDULED,
            'stage' => GameStage::REGULAR
        ]);

        // 2. Set Initial Lineups
        $homeLineup = [];
        for ($i = 1; $i <= 9; $i++) {
            $homeLineup[] = [
                'player_id' => $homePlayers[$i - 1]->id,
                'batting_order' => $i,
                'field_position' => 'P'
            ];
        }
        $homeReserve = [['player_id' => $homePlayers[10]->id]];

        $awayLineup = [];
        for ($i = 1; $i <= 9; $i++) {
            $awayLineup[] = [
                'player_id' => $awayPlayers[$i - 1]->id,
                'batting_order' => $i,
                'field_position' => 'P'
            ];
        }
        $awayReserve = [['player_id' => $awayPlayers[10]->id]];

        $response = $this->postJson('/api/save-lineups', [
            'game_id' => $game->id,
            'home_lineup' => $homeLineup,
            'home_reserve' => $homeReserve,
            'away_lineup' => $awayLineup,
            'away_reserve' => $awayReserve
        ]);

        if ($response->status() !== 200) {
            $response->dump();
        }

        $response->assertStatus(200);

        // 3. Start Game
        $response = $this->postJson("/api/games/{$game->id}/start");
        $response->assertStatus(200);
        $this->assertEquals(GameStatus::LIVE, Game::find($game->id)->status);

        // 4. Record Events (Single)
        $response = $this->postJson('/api/game-events', [
            'game_id' => $game->id,
            'team_id' => $awayTeam->id,
            'player_id' => $awayPlayers[0]->id,
            'event_type' => 'single'
        ]);
        $response->assertStatus(201);
        $this->assertEquals($awayPlayers[0]->id, $response->json('bases.first.id'));

        // 5. Substitution
        $response = $this->postJson('/api/game-lineups/substitute', [
            'game_id' => $game->id,
            'team_id' => $awayTeam->id,
            'outgoing_player_id' => $awayPlayers[0]->id, 
            'incoming_player_id' => $awayPlayers[10]->id,
            'field_position' => 'P'
        ]);
        $response->assertStatus(200);

        // Verify substitution reflected in bases
        $game->refresh();
        $this->assertEquals($awayPlayers[10]->id, $game->first_base_player_id);

        // 6. Inning 3 Rule (Move to Inning 4)
        $game->update(['current_inning' => 4, 'outs' => 0]);

        $response = $this->postJson('/api/game-lineups/update-reserve', [
            'game_id' => $game->id,
            'team_id' => $awayTeam->id,
            'player_id' => $awayPlayers[11]->id,
            'action' => 'add'
        ]);
        $response->assertStatus(422);
        $this->assertStringContainsString('Inning 3', $response->json('message'));
    }
}
