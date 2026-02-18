<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PlayerWebController extends Controller
{
    public function create(League $league, Team $team)
    {
        return view('admin.players.create', compact('league', 'team'));
    }

    public function store(Request $request, League $league, Team $team)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dni' => 'nullable|string|max:255',
            'jersey_number' => [
                'nullable',
                'numeric',
                'unique:players,jersey_number,NULL,id,team_id,' . $team->id
            ],
            'image' => 'nullable|image|max:10240'
        ], [
            'jersey_number.unique' => 'Ese número de camiseta ya está asignado en este equipo.'
        ]);

        $data = $request->only([
            'first_name',
            'last_name',
            'dni',
            'jersey_number'
        ]);

        $data['image'] = null;

        if ($request->hasFile('image')) {

            $manager = new ImageManager(new Driver());

            $imageFile = $request->file('image');

            $filename = uniqid() . '.jpg';

            $image = $manager->read($imageFile)
                ->scale(width: 800)
                ->toJpeg(80);

            Storage::disk('public')->put(
                'players/' . $filename,
                (string) $image
            );

            $data['image'] = 'players/' . $filename;
        }

        $team->players()->create($data);

        return redirect()
            ->route('admin.teams.show', [$league, $team])
            ->with('success', 'Jugador agregado correctamente.');
    }

    public function edit(League $league, Team $team, Player $player)
    {
        return view('admin.players.edit', compact('league', 'team', 'player'));
    }

    public function update(Request $request, League $league, Team $team, Player $player)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dni' => 'nullable|string|max:255',
            'jersey_number' => [
                'nullable',
                'numeric',
                'unique:players,jersey_number,' . $player->id . ',id,team_id,' . $team->id
            ],
            'image' => 'nullable|image|max:10240'
        ], [
            'jersey_number.unique' => 'Ese número de camiseta ya está asignado en este equipo.'
        ]);

        $data = $request->only([
            'first_name',
            'last_name',
            'dni',
            'jersey_number'
        ]);

        if ($request->hasFile('image')) {

            if ($player->image) {
                Storage::disk('public')->delete($player->image);
            }

            $manager = new ImageManager(new Driver());

            $imageFile = $request->file('image');

            $filename = uniqid() . '.jpg';

            $image = $manager->read($imageFile)
                ->scale(width: 800)
                ->toJpeg(80);

            Storage::disk('public')->put(
                'players/' . $filename,
                (string) $image
            );

            $data['image'] = 'players/' . $filename;
        }

        $player->update($data);

        return redirect()
            ->route('admin.teams.show', [$league, $team])
            ->with('success', 'Jugador actualizado correctamente.');
    }

    public function destroy(League $league, Team $team, Player $player)
    {
        if ($player->image) {
            Storage::disk('public')->delete($player->image);
        }

        $player->delete();

        return redirect()
            ->route('admin.teams.show', [$league, $team])
            ->with('success', 'Jugador eliminado correctamente.');
    }

    public function show(League $league, Team $team, Player $player)
    {
        // Juegos jugados (si participó en eventos)
        $gamesPlayed = $player->gameEvents()
            ->select('game_id')
            ->distinct()
            ->count();

        // Hits (asumiendo que event_type = hit)
        $hits = $player->gameEvents()
            ->where('event_type', 'hit')
            ->count();

        // Carreras anotadas
        $runs = \App\Models\GameEvent::where('scored_player_id', $player->id)->count();

        // RBI
        $rbi = $player->gameEvents()->sum('rbi');

        // Turnos al bate (ejemplo simple)
        $atBats = $player->gameEvents()
            ->whereIn('event_type', ['hit', 'out'])
            ->count();

        // Promedio bateo
        $average = $atBats > 0 ? round($hits / $atBats, 3) : 0;

        return view('admin.players.show', compact(
            'league',
            'team',
            'player',
            'gamesPlayed',
            'hits',
            'runs',
            'rbi',
            'atBats',
            'average'
        ));
    }
}