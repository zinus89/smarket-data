<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = ['home_team_id', 'away_team_id', 'competition_id', 'date'];

    public function homeTeam(){
        return $this->belongsTo('App\Team', 'home_team_id');
    }

    public function awayTeam(){
        return $this->belongsTo('App\Team', 'away_team_id');
    }

    public function homeTeamName(){
        return $this->homeTeam()->first()->name;
    }
    public function awayTeamName(){
        return $this->awayTeam()->first()->name;
    }
    public static function firstOrNewMatch($details, $date)
    {
        $teams = Team::firstOrNewTeams($details);
        $match = self::firstOrNew(array(
            'home_team_id' => $teams['homeTeam']->id,
            'away_team_id' => $teams['awayTeam']->id,
            'date' =>$date
        ));
        $match->save();

        return $match;
    }

}
