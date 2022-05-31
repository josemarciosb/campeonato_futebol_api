<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';

    public function players()
    {
        return $this->hasMany(Player::class, 'id_team', 'id');
    }

    public function gameHome()
    {
        return $this->belongsTo(Game::class, 'id_team_home', 'id');
    }

    public function gameVisitor()
    {
        return $this->belongsTo(Game::class, 'id_team_vis', 'id');
    }

    public function championships()
    {
        return $this->belongsToMany(Championship::class, 'championships_teams',  'id_team', 'id_championship')->withPivot([
            'points',
            'goals',
            'yellow_cards',
            'red_cards'
        ])->withTimestamps();
    }

}
