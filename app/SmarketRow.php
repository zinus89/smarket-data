<?php

namespace App;

use App\Constants\Events;
use Illuminate\Database\Eloquent\Model;

class SmarketRow extends Model
{
    protected $fillable = [
        'event', 'details', 'date',
        'backers_stake', 'odds', 'exposure',
        'in_out', 'balance',
    ];

    public function market(){
        return $this->belongsTo('App\Market', 'id');
    }

    public static function createRow($row)
    {
        $row = self::firstOrNew(array(
            'event' => $row['Event'],
            'details' => $row['Details'],
            'date' => $row['Date'],
            'backers_stake' => $row['Backers Stake'],
            'odds' => $row['Odds'],
            'exposure' => $row['Exposure'],
            'in_out' => $row['In/Out'],
            'balance' => $row['Balance'],
        ));
        $row->save();
        return $row;
    }

    public static function marketSettledRows()
    {
        return self::where('event', Events::MARKET_SETTLED)
            // Excluding cancelled bets
            ->where('in_out', '!=', 'SEK0.00')
            ->get();
    }

    public static function wonOrLostRows()
    {
        return self::where('event', 'like', Events::BET_WON.'%')->orWhere('event', 'like', Events::BET_LOST.'%')
            ->get();

    }

    public static function findMarketSettledOnDetailsAndDate($details, $date)
    {
        $date = substr($date,0, 12);
        return self::where('event', Events::MARKET_SETTLED)->where('details', $details)->where('date', 'like', $date.'%')->first();
    }
}
