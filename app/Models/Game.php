<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'games';

    public function teamHome()
    {
        return $this->hasOne(Team::class, 'id_team_home', 'id');
    }

    public function teamVisitor()
    {
        return $this->hasOne(Team::class, 'id_team_vis', 'id');
    }

    public function championship()
    {
        return $this->belongsTo(Championship::class, 'id_championship', 'id');
    }

    public function goals()
    {
        return $this->hasMany(Goal::class, 'id_game', 'id');
    }
}
