<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Player;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_date',
        'dice1',
        'dice2',
        'status',
        'player_id'
    ];

    public function player() {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }

}
