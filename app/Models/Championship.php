<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{
    use HasFactory;

    protected $table = 'championships';

    public function games()
    {
        return $this->hasMany(Game::class, 'id_championship', 'id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'championships_teams', 'id_championship', 'id_team')->withPivot([
            'points',
            'goals',
            'yellow_cards',
            'red_cards'
        ])->withTimestamps();
    }

    public function goals()
    {
        return $this->hasMany(Goal::class, 'id_championship', 'id');
    }
}
