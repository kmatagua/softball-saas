<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LeagueWebController;
use App\Http\Controllers\Web\GameWebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('admin')->group(function () {

    Route::get('/leagues', [LeagueWebController::class, 'index'])
        ->name('admin.leagues.index');

    Route::get('/leagues/create', [LeagueWebController::class, 'create'])
        ->name('admin.leagues.create');

    Route::post('/leagues', [LeagueWebController::class, 'store'])
        ->name('admin.leagues.store');

    Route::get('/leagues/{league}', [LeagueWebController::class, 'show'])
        ->name('admin.leagues.show');

});
//Route::get('/', [LeagueWebController::class, 'index']);
//Route::get('/ligas/{slug}', [LeagueWebController::class, 'show'])->name('leagues.show');
/*Route::get('/ligas/{slug}/juegos/{id}', [GameWebController::class, 'show'])->name('games.show');
Route::get('/ligas/{slug}/juegos/{id}/anotador',
    [GameWebController::class, 'scoreboard'])
    ->name('games.scoreboard');
Route::get('/ligas/{slug}/juegos/{id}/lineup',
    [GameWebController::class, 'lineup'])
    ->name('games.lineup');
Route::get('/ligas/{slug}/juegos/{id}/lineup/manager',
    [GameWebController::class, 'managerLineup'])
    ->name('games.lineup.manager');

Route::get('/ligas/{slug}/juegos/{id}/lineup/scorekeeper',
    [GameWebController::class, 'scorekeeperLineup'])
    ->name('games.lineup.scorekeeper');*/