<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BetType extends Model
{
    protected $fillable = ['name', 'lay'];

    protected $hidden = ['id', 'created_at', 'updated_at', 'marketBetTypesWon','marketBetTypesLost'];
    protected $appends = array(
        'marketBetTypesWon','marketBetTypesLost', 'nMarkets', 'nMarketsWon',
        'nMarketsLost', 'ratio', 'sInOut', 'aOdds', 'aOddsWon',
        'aOddsLost', 'hOddsWon', 'hOddsLost', 'lOddsWon', 'lOddsLost');

    public function getMarketBetTypesWonAttribute()
    {
        return $this->marketBetTypes()->where('markets.in_out', '>', 0)->get();
    }
    public function getMarketBetTypesLostAttribute()
    {
        return $this->marketBetTypes()->where('markets.in_out', '<', 0)->get();
    }

    public function getNMarketsAttribute()
    {
        return $this->marketBetTypes()->count();
    }

    public function getNMarketsWonAttribute()
    {
        return $this->marketBetTypes()->where('markets.in_out', '>', 0)->count();
    }

    public function getNMarketsLostAttribute()
    {
        return $this->marketBetTypes()->where('markets.in_out', '<', 0)->count();
    }

    public function getRatioAttribute(){
        if(!$this->nMarkets){
            return 0;
        }
        return 100 * ($this->nMarketsWon / $this->nMarkets);
    }

    public function getSInOutAttribute()
    {
        return $this->marketBetTypes()->sum('in_out');
    }

    public function getAOddsAttribute()
    {
        $markets = $this->marketBetTypes()->get();
        return $markets->avg('aOdds');
    }

    public function getAOddsWonAttribute()
    {
        $markets = $this->marketBetTypesWon;
        return $markets->avg('aOdds');
    }

    public function getAOddsLostAttribute()
    {
        $markets = $this->marketBetTypesLost;
        return $markets->avg('aOdds');
    }

    public function getHOddsWonAttribute()
    {
        $market = $this->marketBetTypesWon->sortByDesc('hOdds')->values()->first();
        if (!isset($market)) {
            return;
        }
        return $market->hOdds;
    }

    public function getHOddsLostAttribute()
    {
        $market = $this->marketBetTypesLost->sortByDesc('hOdds')->values()->first();
        if (!isset($market)) {
            return;
        }
        return $market->hOdds;
    }

    public function getLOddsWonAttribute()
    {
        $market = $this->marketBetTypesWon->sortBy('lOdds')->values()->first();
        if (!isset($market)) {
            return;
        }
        return $market->lOdds;
    }

    public function getLOddsLostAttribute()
    {
        $market = $this->marketBetTypesLost->sortBy('lOdds')->values()->first();
        if (!isset($market)) {
            return;
        }
        return $market->lOdds;
    }

    public function marketBetTypes()
    {
        return $this->hasOne('App\MarketModels\MarketBetType', 'bet_type_id');
    }

    public static function firstOrNewBetType($event, $details, $match)
    {
        // Example event Market Placed · Against Over 5.5 goals
        // Example event Market Placed · For Over 5.5 goals

        $forPosition = strpos($event, 'For');
        $againstPosition = strpos($event, 'Against');

        $yesPosition = strpos($event, 'Yes');
        $noPosition = strpos($event, 'No');

        // Edge case for (No)rthern Ireland etc
        if ($noPosition && isset($event[$noPosition + 2])) {
            $noPosition = false;
        }

        $homeTeamName = $match->homeTeamName();
        $awayTeamName = $match->awayTeamName();
        $homeTeamPosition = strpos($event, $homeTeamName);
        $awayTeamPosition = strpos($event, $awayTeamName);

        if ($forPosition) {
            $lay = false;
            $betTypeName = substr($event, $forPosition);

        } else if ($againstPosition) {
            $lay = true;
            $betTypeName = substr($event, $againstPosition);
        } else {
            throw new \Exception('Cant find Market placed for or against in smarket_rows.event: ' . $event);
        }

        if ($homeTeamPosition) {
            $betTypeName = str_replace($homeTeamName, 'Home Team', $betTypeName);
        }
        if ($awayTeamPosition) {
            $betTypeName = str_replace($awayTeamName, 'Away Team', $betTypeName);
        }

        if ($yesPosition) {
            $colonPosition = strpos($details, ':');
            $type = substr($details, $colonPosition + 2);
            $betTypeName = 'Yes: ' . $type;

        } else if ($noPosition) {
            $colonPosition = strpos($details, ':');
            $type = substr($details, $colonPosition + 2);
            $betTypeName = 'No: ' . $type;
        }
        $betTypeName = str_replace('For', '', $betTypeName);
        $betTypeName = str_replace('Against', '', $betTypeName);
        $betType = self::firstOrNew(array(
            'name' => $betTypeName,
            'lay' => $lay
        ));
        $betType->save();

        return $betType;
    }
}
