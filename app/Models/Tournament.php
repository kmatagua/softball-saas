<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'league_id',
        'name',
        'qualifies_per_group',
        'groups_count',
        'is_active',
        'points_per_win',
        'points_per_draw',
        'points_per_loss',
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function standingsByGroup()
    {
        $standings = [];

        foreach ($this->groups as $group) {

            $teams = $group->teams;
            $totalTeams = $teams->count();
            $qualifyCount = $this->qualifies_per_group;

            $table = [];

            foreach ($teams as $team) {

                $games = $this->games()
                    ->where('group_id', $group->id)
                    ->where(function ($q) use ($team) {
                        $q->where('home_team_id', $team->id)
                          ->orWhere('away_team_id', $team->id);
                    })
                    ->get();

                $finishedGames = $games->where('status', 'finished');
                $played = $finishedGames->count();

                $won = 0;
                $lost = 0;
                $draw = 0;
                $runsFor = 0;
                $runsAgainst = 0;

                foreach ($finishedGames as $game) {

                    if ($game->home_team_id == $team->id) {

                        $runsFor += $game->home_score;
                        $runsAgainst += $game->away_score;

                        if ($game->home_score > $game->away_score) {
                            $won++;
                        } elseif ($game->home_score < $game->away_score) {
                            $lost++;
                        } else {
                            $draw++;
                        }

                    } else {

                        $runsFor += $game->away_score;
                        $runsAgainst += $game->home_score;

                        if ($game->away_score > $game->home_score) {
                            $won++;
                        } elseif ($game->away_score < $game->home_score) {
                            $lost++;
                        } else {
                            $draw++;
                        }
                    }
                }

                $points =
                    ($won * $this->points_per_win) +
                    ($draw * $this->points_per_draw) +
                    ($lost * $this->points_per_loss);

                $totalMatchesPerTeam = $totalTeams - 1;
                $remainingMatches = $totalMatchesPerTeam - $played;

                $maxPossiblePoints =
                    $points + ($remainingMatches * $this->points_per_win);

                $table[] = [
                    'team'      => $team,
                    'pj'        => $played,
                    'pg'        => $won,
                    'pe'        => $draw,
                    'pp'        => $lost,
                    'cf'        => $runsFor,
                    'cc'        => $runsAgainst,
                    'dif'       => $runsFor - $runsAgainst,
                    'pts'       => $points,
                    'max_pts'   => $maxPossiblePoints,
                ];
            }

            // Ordenar por puntos, diferencia y carreras a favor
            usort($table, function ($a, $b) {
                return [
                    $b['pts'],
                    $b['dif'],
                    $b['cf']
                ] <=> [
                    $a['pts'],
                    $a['dif'],
                    $a['cf']
                ];
            });

            // ==============================
            // DETERMINAR ESTADOS MATEMÁTICOS
            // ==============================

            foreach ($table as $index => &$row) {

                $row['position'] = $index + 1;

                $teamsAlreadyAbove = 0;
                $teamsThatCanPass = 0;

                foreach ($table as $other) {

                    if ($other['team']->id == $row['team']->id) {
                        continue;
                    }

                    // Equipos que ya lo superaron matemáticamente
                    if ($other['pts'] > $row['max_pts']) {
                        $teamsAlreadyAbove++;
                    }

                    // Equipos que aún pueden superarlo
                    if ($other['max_pts'] > $row['pts']) {
                        $teamsThatCanPass++;
                    }
                }

                // 🔴 Eliminado
                if ($teamsAlreadyAbove >= $qualifyCount) {

                    $row['status'] = 'eliminated';

                }
                // 🟢 Clasificado
                elseif ($teamsThatCanPass < $qualifyCount) {

                    $row['status'] = 'qualified';

                }
                // 🟡 Aún en pelea
                else {

                    $row['status'] = 'neutral';
                }
            }

            $standings[$group->name] = $table;
        }

        return $standings;
    }
}