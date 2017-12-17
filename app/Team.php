<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'country', 'footlive_name'];

    public static function firstOrNewTeams($details)
    {
        $teamNames = self::guessTeams($details);

        $homeTeam = self::firstOrNew(array('name' => $teamNames['homeTeam']));
        $homeTeam->save();

        $awayTeam = self::firstOrNew(array('name' => $teamNames['awayTeam']));
        $awayTeam->save();

        return array('homeTeam' => $homeTeam, 'awayTeam' => $awayTeam);
    }

    private static function guessTeams($details)
    {
        // Example details Watford vs. Manchester City / Over/under 5.5 for Watford vs. Manchester City

        $vsPosition = strpos($details, 'vs.');
        if(!$vsPosition){
            throw new \Exception('Cant find position for vs. in smarket_rows.details: ' . $details);
        }
        $firstSlashPosition = strpos($details, ' / ');
        $teams['homeTeam'] = substr($details, 0, $vsPosition-1);
        $teams['awayTeam'] = substr($details, $vsPosition + 4, ($firstSlashPosition - ($vsPosition + 4)));

        return $teams;
    }
}
