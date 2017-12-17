<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $fillable = [
        'smarket_row_id', 'match_id', 'bet_type_id', 'in_out','won', 'date'
    ];

    public function match(){
        return $this->belongsTo('App\Match');
    }
    public function bets(){
        return $this->belongsTo('App\Bet', 'id', 'market_id');
    }

    public function updateBetTypeId($betTypeId){
        $this->bet_type_id = $betTypeId;
        $this->save();
    }

    public static function firstOrNewMarket($row)
    {
        $dateTime = new DateTime($row->date);
        $date = ($dateTime->format('Y-m-d H:i'));
        $match = Match::firstOrNewMatch($row->details, $date);

        $inout = str_replace('SEK', '', $row->in_out);
        $inout = str_replace(',', '', $inout);
        $market = self::firstOrCreate(array(
            'smarket_row_id' => $row->id,
            'match_id' => $match->id,
            'in_out' => $inout,
            'won' => $inout > 0,
            'date' => $date
        ));
        $market->save();
    }
}
