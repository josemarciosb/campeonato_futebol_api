<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $table = 'goals';

    public function player()
    {
        return $this->belongsTo(Player::class, 'id_player', 'id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'id_game', 'id');
    }

    public function championship()
    {
        return $this->belongsTo(Championship::class, 'id_championship', 'id');
    }
}
