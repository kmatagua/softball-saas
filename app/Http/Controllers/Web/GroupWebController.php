<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupWebController extends Controller
{
    public function create(League $league)
    {
        return view('admin.groups.create', compact('league'));
    }

    public function store(Request $request, League $league)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $league->groups()->create([
            'name' => $request->name
        ]);

        return redirect()
            ->route('admin.leagues.show', $league)
            ->with('success', 'Grupo creado correctamente.');
    }

    public function edit(League $league, Group $group)
    {
        return view('admin.groups.edit', compact('league', 'group'));
    }

    public function update(Request $request, League $league, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $group->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('admin.leagues.show', $league)
            ->with('success', 'Grupo actualizado.');
    }

    public function destroy(League $league, Group $group)
    {
        $group->delete();

        return redirect()
            ->route('admin.leagues.show', $league)
            ->with('success', 'Grupo eliminado.');
    }
}