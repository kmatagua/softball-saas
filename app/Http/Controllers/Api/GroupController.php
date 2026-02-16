<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Game;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function standings($groupId)
    {
        $group = Group::with('teams')->findOrFail($groupId);

        $teams = $group->teams;

        $standings = [];

        foreach ($teams as $team) {

            $games = Game::where(function ($query) use ($team) {
                    $query->where('home_team_id', $team->id)
                          ->orWhere('away_team_id', $team->id);
                })
                ->where('status', 'finished')
                ->get();

            $played = $games->count();
            $won = 0;
            $lost = 0;
            $runsFor = 0;
            $runsAgainst = 0;

            foreach ($games as $game) {

                if ($game->home_team_id == $team->id) {

                    $runsFor += $game->home_score;
                    $runsAgainst += $game->away_score;

                    if ($game->home_score > $game->away_score) {
                        $won++;
                    } elseif ($game->home_score < $game->away_score) {
                        $lost++;
                    }

                } else {

                    $runsFor += $game->away_score;
                    $runsAgainst += $game->home_score;

                    if ($game->away_score > $game->home_score) {
                        $won++;
                    } elseif ($game->away_score < $game->home_score) {
                        $lost++;
                    }
                }
            }

            $standings[] = [
                'team_id' => $team->id,
                'team_name' => $team->name,
                'played' => $played,
                'won' => $won,
                'lost' => $lost,
                'runs_for' => $runsFor,
                'runs_against' => $runsAgainst,
                'points' => $won * 2
            ];
        }

        usort($standings, function ($a, $b) {
            return $b['points'] <=> $a['points']
                ?: ($b['runs_for'] - $b['runs_against'])
                <=> ($a['runs_for'] - $a['runs_against']);
        });

        return response()->json($standings);
    }
}