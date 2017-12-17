<?php
/**
 * Created by PhpStorm.
 * User: joy_f
 * Date: 2017-11-24
 * Time: 21:37
 */

namespace App\MarketModels;

use App\Market;

class MarketBetType extends Market
{
    protected $table = 'markets';
    protected $appends = array('aOdds', 'hOdds', 'lOdds');

    public function getAOddsAttribute()
    {
        return $this->bets()->avg('odds');
    }

    public function getHOddsAttribute()
    {
        $bet = $this->bets()->orderBy('bets.odds', 'desc')->first();
        if (!isset($bet)) {
            return;
        }
        return $bet->odds;
    }

    public function getLOddsAttribute()
    {
        $bet = $this->bets()->orderBy('bets.odds')->first();
        if (!isset($bet)) {
            return;
        }
        return $bet->odds;
    }
}