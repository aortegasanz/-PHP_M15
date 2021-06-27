<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('jwt.auth')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

    /* POST   /players                : crea un jugador */
    Route::post('players',      [PlayerController::class, 'store'])->name('players.store');
    /* PUT    /players/{id}           : modifica el nom del jugador */
    Route::post('players/{id}', [PlayerController::class, 'update'])->name('players.update');

    /* GET    /players/ : retorna el llistat de tots els jugadors del sistema amb el seu percentatge mig d’èxits */
    Route::get ('players', [PlayerController::class, 'list'])->name('players.list');

    /* GET    /players/ranking : retorna el ranking mig de tots els jugadors del sistema. És a dir, el percentatge mig d’èxits. */
    Route::get ('players/ranking', [PlayerController::class, 'ranking'])->name('players.ranking');

    /* GET    /players/ranking/loser : retorna el jugador amb pitjor percentatge d’èxit */
    Route::get ('players/ranking/loser', [PlayerController::class, 'rankingLoser'])->name('players.rankingLoser');

    /* GET    /players/ranking/winner : retorna el jugador amb pitjor percentatge d’èxit. */
    Route::get ('players/ranking/winner',  [PlayerController::class, 'rankingWinner'])->name('players.rankingWinner');

    /* POST   /players/{id}/games/    : un jugador específic realitza una tirada dels daus. */
    Route::post('players/{id}/games/', [PlayerController::class, 'gameShoot'])->name('players.gameShoot');
    /* DELETE /players/{id}/games     : elimina les tirades del jugador */
    Route::delete('players/{id}/games/', [PlayerController::class, 'gameDelete'])->name('players.gameDelete');
    /* GET    /players/{id}/games     : retorna el llistat de jugades per un jugador. */
    Route::get('players/{id}/games/', [PlayerController::class, 'gameList'])->name('players.gameList');

});