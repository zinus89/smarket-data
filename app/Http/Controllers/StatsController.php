<?php

namespace App\Http\Controllers;

use App\BetType;
use App\Market;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function betTypeView(){
        return View('stats.bet_type');
    }
    public function oddsView(){
        return View('stats.odds');
    }

    public function getByBetType(Request $request)
    {
        $betTypes = BetType::all()->sortByDesc('sInOut')->values()->toArray();
        $betTypes = array_map(function($betType){
            $betType['ratio'] = round($betType['ratio']) ;
            $betType['aOdds'] = round($betType['aOdds']) ;
            $betType['aOddsWon'] = isset($betType['aOddsWon']) ? round($betType['aOddsWon'], 2) : '-';
            $betType['aOddsLost'] = isset($betType['aOddsLost']) ? round($betType['aOddsLost'], 2) : '-' ;
            $betType['hOddsWon'] = isset($betType['hOddsWon']) ? $betType['hOddsWon'] : '-';
            $betType['hOddsLost'] = isset($betType['hOddsLost']) ? $betType['hOddsLost'] : '-' ;
            $betType['lOddsWon'] = isset($betType['lOddsWon']) ? $betType['lOddsWon'] : '-';
            $betType['lOddsLost'] = isset($betType['lOddsLost']) ? $betType['lOddsLost'] : '-' ;
            return $betType;
        }, $betTypes);
        return json_encode($betTypes);
    }
    public function getByOdds(Request $request)
    {
        $betTypes = Market::all();
        return json_encode($betTypes);
    }
    public function getByMarket(Request $request)
    {
        $betTypes = Market::all();
        return json_encode($betTypes);
    }
}
