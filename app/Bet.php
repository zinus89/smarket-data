<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    protected $fillable = [
        'smarket_row_id', 'market_id', 'backers_stake', 'date', 'odds'];

    public static function firstOrNewBet($row)
    {
        $dateTime = new DateTime($row->date);
        $date = ($dateTime->format('Y-m-d H:i'));

        $marketSettledSmarketRow = SmarketRow::findMarketSettledOnDetailsAndDate($row->details, $row->date);
        if(!isset($marketSettledSmarketRow)){
            throw new \Exception('Cant find Market settled in smarket_rows on details: ' . $row->details);
        }

        $market = Market::where('smarket_row_id', $marketSettledSmarketRow->id)->first();
        if(!isset($market)){
            throw new \Exception('Cant find Market related to smarket_row.id: ' . $marketSettledSmarketRow->id);
        }
        $match = $market->match()->first();
        $betType = BetType::firstOrNewBetType($row->event, $row->details, $match);
        $market->updateBetTypeId($betType->id);

        $backers_stake = str_replace('SEK', '', $row->backers_stake);
        $backers_stake = str_replace(',', '', $backers_stake);

        $bet = self::firstOrNew(array(
            'smarket_row_id' => $row->id,
            'market_id' => $market->id,
            'backers_stake' => $backers_stake,
            'odds' => $row->odds,
            'date' => $date,
        ));
        $bet->save();
    }
}
