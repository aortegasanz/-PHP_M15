<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Player;

class PlayerController extends Controller
{
    //

    public function store(Request $request) {
        $player = Player::create($request->all());
        return response()->json(compact('player'));
    }

    public function update(Request $request, $id) {
        $player = Player::where('id', $id);
        $player->name = $request->name;
        $player->save();
        return response()->json(compact('player'));
    }

    public function list() {
        $players = Player::all();
        return response()->json(['players' => $players], 200);
    }

}
