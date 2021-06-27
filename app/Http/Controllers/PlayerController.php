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
        if ($request->type == 1) {
            // Type = 1 (Named Player)
            $player = Player::where('name', $request->name)->first();
            if (!$player) {
                $player = Player::create([
                  'name' => $request->name,
                  'type' => $request->type,
                ]);
            } else {
                return response()->json([
                  'success' => false,
                  'error' => 'Duplicated Player Name ('.$request->name.')',
                ], 500);
            }
        } elseif ($request->type == 0) {
            // Type = 0 (Anonymous Player)
            $player = Player::create([
                'name' => 'Anonymous',
                'type' => $request->type,
            ]);
        } else {
          return response()->json([
              'success' => false,
              'error' => 'Undefined Player Type ('.$request->type.')',
          ], 500);
        }
        
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
        $sql = "SELECT P.id, P.name,
                      (select count(*)
                        from games as tempG1
                        where tempG1.player_id = P.id
                        group by tempG1.player_id) as Total,
                      (select count(*)
                        from games as tempG2
                        where tempG2.player_id = P.id
                          and tempG2.status = 0
                        group by status) as LoserShoots,
                      (select count(*)
                        from games as tempG3
                        where tempG3.player_id = P.id
                          and tempG3.status = 1
                        group by status) as WinnerShoots,
                      (
                      (select count(*)
                        from games as tempG4
                        where tempG4.player_id = P.id
                          and tempG4.status = 1
                        group by status) * 100)
                      /
                      (select count(*)
                        from games as tempG5
                        where tempG5.player_id = P.id
                        group by tempG5.player_id)
                      as Promedio
                 FROM games as G right join players as P on (G.player_id = P.id)
                GROUP BY P.id, P.name";
        $ranking = DB::select($sql);
        return response()->json(['ranking' => $ranking], 200);
    }

    /* Retorna el ranking mig de tots els jugadors del sistema. És a dir, el percentatge mig d’èxits. */
    public function ranking() {
        $sql = "SELECT P.id, P.name,
                      (select count(*)
                        from games as tempG1
                        where tempG1.player_id = P.id
                        group by tempG1.player_id) as Total,
                      (select count(*)
                        from games as tempG2
                        where tempG2.player_id = P.id
                          and tempG2.status = 0
                        group by status) as LoserShoots,
                      (select count(*)
                        from games as tempG3
                        where tempG3.player_id = P.id
                          and tempG3.status = 1
                        group by status) as WinnerShoots,
                      (
                      (select count(*)
                        from games as tempG4
                        where tempG4.player_id = P.id
                          and tempG4.status = 1
                        group by status) * 100)
                      /
                      (select count(*)
                        from games as tempG5
                        where tempG5.player_id = P.id
                        group by tempG5.player_id)
                      as Promedio
                FROM games as G right join players as P on (G.player_id = P.id)
                GROUP BY P.id, P.name
                ORDER BY Promedio DESC";
        $ranking = DB::select($sql);
        return response()->json(['ranking' => $ranking], 200);
    }

    /* Retorna el jugador amb pitjor percentatge d’èxit */
    public function rankingLoser () {
        $sql = "SELECT P.id, P.name,
                      (select count(*)
                        from games as tempG1
                        where tempG1.player_id = P.id
                        group by tempG1.player_id) as Total,
                      (select count(*)
                        from games as tempG2
                        where tempG2.player_id = P.id
                          and tempG2.status = 0
                        group by status) as LoserShoots,
                      (select count(*)
                        from games as tempG3
                        where tempG3.player_id = P.id
                          and tempG3.status = 1
                        group by status) as WinnerShoots,
                      (
                      (select count(*)
                        from games as tempG4
                        where tempG4.player_id = P.id
                          and tempG4.status = 1
                        group by status) * 100)
                      /
                      (select count(*)
                        from games as tempG5
                        where tempG5.player_id = P.id
                        group by tempG5.player_id)
                      as Promedio
                FROM games as G right join players as P on (G.player_id = P.id)
                GROUP BY P.id, P.name
                ORDER BY Promedio ASC";
        $player = DB::select($sql)[0];
        return response()->json(['player' => $player], 200);
    }

    /* Retorna el jugador amb pitjor percentatge d’èxit. */
    public function rankingWinner () {
        $sql = "SELECT P.id, P.name,
                        (select count(*)
                          from games as tempG1
                          where tempG1.player_id = P.id
                          group by tempG1.player_id) as Total,
                        (select count(*)
                          from games as tempG2
                          where tempG2.player_id = P.id
                            and tempG2.status = 0
                          group by status) as LoserShoots,
                        (select count(*)
                          from games as tempG3
                          where tempG3.player_id = P.id
                            and tempG3.status = 1
                          group by status) as WinnerShoots,
                        (
                        (select count(*)
                          from games as tempG4
                          where tempG4.player_id = P.id
                            and tempG4.status = 1
                          group by status) * 100)
                        /
                        (select count(*)
                          from games as tempG5
                          where tempG5.player_id = P.id
                          group by tempG5.player_id)
                        as Promedio
                  FROM games as G right join players as P on (G.player_id = P.id)
                  GROUP BY P.id, P.name
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
