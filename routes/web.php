<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LeagueWebController;
use App\Http\Controllers\Web\GroupWebController;
use App\Http\Controllers\Web\TeamWebController;
use App\Http\Controllers\Web\PlayerWebController;

Route::get('/', function () {
    return redirect()->route('admin.leagues.index');
});

Route::prefix('admin')->group(function () {

    // LEAGUES
    Route::get('/leagues', [LeagueWebController::class, 'index'])
        ->name('admin.leagues.index');

    Route::get('/leagues/create', [LeagueWebController::class, 'create'])
        ->name('admin.leagues.create');

    Route::post('/leagues', [LeagueWebController::class, 'store'])
        ->name('admin.leagues.store');

    Route::get('/leagues/{league}', [LeagueWebController::class, 'show'])
        ->name('admin.leagues.show');

    Route::get('/leagues/{league}/edit', [LeagueWebController::class, 'edit'])
        ->name('admin.leagues.edit');

    Route::put('/leagues/{league}', [LeagueWebController::class, 'update'])
        ->name('admin.leagues.update');

    Route::delete('/leagues/{league}', [LeagueWebController::class, 'destroy'])
        ->name('admin.leagues.destroy');


    // GROUPS (dentro de League)
    Route::get('/leagues/{league}/groups/create', [GroupWebController::class, 'create'])
        ->name('admin.groups.create');

    Route::post('/leagues/{league}/groups', [GroupWebController::class, 'store'])
        ->name('admin.groups.store');


    // TEAMS (dentro de League)
    Route::get('/leagues/{league}/teams/create', [TeamWebController::class, 'create'])
        ->name('admin.teams.create');

    Route::post('/leagues/{league}/teams', [TeamWebController::class, 'store'])
        ->name('admin.teams.store');

    // GROUPS
    Route::get('/leagues/{league}/groups/{group}/edit', [GroupWebController::class, 'edit'])
        ->name('admin.groups.edit');

    Route::put('/leagues/{league}/groups/{group}', [GroupWebController::class, 'update'])
        ->name('admin.groups.update');

    Route::delete('/leagues/{league}/groups/{group}', [GroupWebController::class, 'destroy'])
        ->name('admin.groups.destroy');


    // TEAMS
    Route::get('/leagues/{league}/teams/{team}/edit', [TeamWebController::class, 'edit'])
        ->name('admin.teams.edit');

    Route::put('/leagues/{league}/teams/{team}', [TeamWebController::class, 'update'])
        ->name('admin.teams.update');

    Route::delete('/leagues/{league}/teams/{team}', [TeamWebController::class, 'destroy'])
        ->name('admin.teams.destroy');

    Route::get('/leagues/{league}/teams/{team}', [TeamWebController::class, 'show'])
        ->name('admin.teams.show');

    //JUGADORES (dentro de Team) --- IGNORE ---
    Route::get('/leagues/{league}/teams/{team}/players/create', [PlayerWebController::class, 'create'])
        ->name('admin.players.create');

    Route::post('/leagues/{league}/teams/{team}/players', [PlayerWebController::class, 'store'])
        ->name('admin.players.store');

    Route::get('/leagues/{league}/teams/{team}/players/{player}/edit', [PlayerWebController::class, 'edit'])
        ->name('admin.players.edit');

    Route::put('/leagues/{league}/teams/{team}/players/{player}', [PlayerWebController::class, 'update'])
        ->name('admin.players.update');

    Route::delete('/leagues/{league}/teams/{team}/players/{player}', [PlayerWebController::class, 'destroy'])
        ->name('admin.players.destroy');

    Route::get('/leagues/{league}/teams/{team}/players/{player}', [PlayerWebController::class, 'show'])
        ->name('admin.players.show');

});