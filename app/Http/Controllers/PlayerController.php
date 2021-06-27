<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Player;
use App\Models\Game;

class PlayerController extends Controller
{
    //
    //  Params ($request)
    //  name (string)
    //  type (1: Registered Player | 0: Anonymous)
    public function store(Request $request) {
        $player = Player::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);
        return response()->json(compact('player'));
    }

    public function update(Request $request, $id) {
        $player = Player::findOrFail($id);
        $player->name = $request->name;
        $player->save();
        return response()->json(compact('player'));
    }

    /* Retorna el llistat de tots els jugadors del sistema amb el seu percentatge mig d’èxits */
    public function list() {
        $sql = "SELECT player_id, P.name, count(*) as Total,
                 (select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 0
                   group by status) as LoserShoots,
                 (select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 1
                   group by status) as WinnerShoots,
                 ((select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 1
                   group by status) * 100) / count(*) as Promedio
            FROM games as G join players as P on (G.player_id = P.id)
           GROUP BY player_id, P.name";
        $ranking = DB::select($sql);
        return response()->json(['ranking' => $ranking], 200);
    }

    /* Retorna el ranking mig de tots els jugadors del sistema. És a dir, el percentatge mig d’èxits. */
    public function ranking() {
        $sql = "SELECT player_id, P.name, count(*) as Total,
                 (select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 0
                   group by status) as LoserShoots,
                 (select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 1
                   group by status) as WinnerShoots,
                 ((select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 1
                   group by status) * 100) / count(*) as Promedio
            FROM games as G join players as P on (G.player_id = P.id)
           GROUP BY player_id, P.name
           ORDER BY Promedio DESC";
        $ranking = DB::select($sql);
        return response()->json(['ranking' => $ranking], 200);
    }

    /* Retorna el jugador amb pitjor percentatge d’èxit */
    public function rankingLoser () {
        $sql = "SELECT player_id, P.name, count(*) as Total,
                 (select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 0
                   group by status) as LoserShoots,
                 (select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 1
                   group by status) as WinnerShoots,
                 ((select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 1
                   group by status) * 100) / count(*) as Promedio
            FROM games as G join players as P on (G.player_id = P.id)
           GROUP BY player_id, P.name
           ORDER BY Promedio ASC";
        $player = DB::select($sql)[0];
        return response()->json(['player' => $player], 200);
    }

    /* Retorna el jugador amb pitjor percentatge d’èxit. */
    public function rankingWinner () {
        $sql = "SELECT player_id, P.name, count(*) as Total,
                 (select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 0
                   group by status) as LoserShoots,
                 (select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 1
                   group by status) as WinnerShoots,
                 ((select count(*)
                    from games as tempG
                   where tempG.player_id = G.player_id
                     and tempG.status = 1
                   group by status) * 100) / count(*) as Promedio
            FROM games as G join players as P on (G.player_id = P.id)
           GROUP BY player_id, P.name
           ORDER BY Promedio DESC";
        $player = DB::select($sql)[0];
        return response()->json(['player' => $player], 200);
    }

    public function gameShoot(Request $request, $id) {
        $dice1 = rand(1, 6);
        $dice2 = rand(1, 6);
        if (($dice1 + $dice2) == 7) {
            $status = 1;
        } else {
            $status = 0;
        }
        $game = Game::create([
            'game_date' => Carbon::now(),
            'dice1' => $dice1,
            'dice2' => $dice2,
            'status' => $status,
            'player_id'  => $id,            
        ]);
        return response()->json(['game' => $game], 200);
    }

    public function gameDelete(Request $request, $id) {
        Game::where('player_id', $id)->delete();
        return response()->json(['Ok' => 'Deleted Succesfully'], 200);
    }

    public function gameList(Request $request, $id) {
        $games = Game::where('player_id', $id)->get();
        return response()->json(['games' => $games], 200);        
    }

}
