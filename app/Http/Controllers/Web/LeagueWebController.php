<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LeagueWebController extends Controller
{
    public function index()
    {
        $leagues = League::latest()->get();
        return view('admin.leagues.index', compact('leagues'));
    }

    public function create()
    {
        return view('admin.leagues.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'active' => true,
            'image' => null
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('leagues', 'public');
        }

        League::create($data);

        return redirect()
            ->route('admin.leagues.index')
            ->with('success', 'Liga creada correctamente');
    }

    public function show(League $league)
    {
        $league->load([
            'groups.teams',
            'teams.players'
        ]);

        return view('admin.leagues.show', compact('league'));
    }

    public function edit(League $league)
    {
        return view('admin.leagues.edit', compact('league'));
    }

    public function update(Request $request, League $league)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ];

        if ($request->hasFile('image')) {

            // borrar imagen anterior
            if ($league->image) {
                Storage::disk('public')->delete($league->image);
            }

            $data['image'] = $request->file('image')
                ->store('leagues', 'public');
        }

        $league->update($data);

        return redirect()
            ->route('admin.leagues.index')
            ->with('success', 'Liga actualizada correctamente');
    }

    public function destroy(League $league)
    {
        // eliminar imagen antes de borrar registro
        if ($league->image) {
            Storage::disk('public')->delete($league->image);
        }

        $league->delete();

        return redirect()
            ->route('admin.leagues.index')
            ->with('success', 'Liga eliminada correctamente');
    }
}