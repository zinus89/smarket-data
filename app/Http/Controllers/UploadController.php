<?php

namespace App\Http\Controllers;

use App\ExceptionLog;
use App\Market;
use App\Bet;
use App\SmarketRow;
use App\Team;
use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Statement;
use Goutte;

class UploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function uploadCsv(Request $request)
    {
        $request->user();
        $file = $request->file();
        $path = ($file['image']->path());
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(1);

        $stmt = (new Statement())
            ->offset(1);

        $records = $stmt->process($csv);
        foreach ($records as $record) {
            SmarketRow::createRow($record);
        }
        $settledRows = SmarketRow::marketSettledRows();
        foreach ($settledRows as $settledRow) {
            try {
                Market::firstOrNewMarket($settledRow);
            } catch (\Exception $e) {
                ExceptionLog::createLog($settledRow->id, $e);
                continue;
            }
        }
        $wonLostRows = SmarketRow::wonOrLostRows();
        foreach ($wonLostRows as $wonLostRow) {
            try {
                Bet::firstOrNewBet($wonLostRow);

            } catch (\Exception $e) {
                ExceptionLog::createLog($wonLostRow->id, $e);
                continue;
            }
        }
    }

    //Todo: Work in progress
    public function syncExternalStats()
    {
        $teams = Team::all();
        foreach ($teams as $team) {
            $name = $team->name;
            $name = str_replace('Å', 'A', $name);
            $name = str_replace('å', 'a', $name);
            $name = str_replace('Ä', 'A', $name);
            $name = str_replace('ä', 'a', $name);
            $name = str_replace('Ö', 'O', $name);
            $name = str_replace('ö', 'o', $name);

            $firstLetter = substr($name, 0, 1);
            $names = \Cache::rememberForever('team-' . $firstLetter, function () use ($team, $firstLetter) {
                $crawler = Goutte::request('GET', 'http://www.footlive.com/team/' . $firstLetter);
                $names = $crawler->filter('div .teamListItem > a')->each(function ($node) {
                    return $node->text();
                });
                return $names;
            });

            $results = $this->array_search_partial($names, $name);

            if(count($results) > 0){
                $team->footlive_name = $names[$results[0]];
                $team->save();
            }
        }
//        $crawler = Goutte::request('GET', 'http://www.footlive.com/team/c');
//
//        $crawler->filter('div .teamListItem > a')->each(function ($node) {
//            $text = ($node->text());
//            $link = ($node->attr('href'));
//            var_dump($text);
//        });
//        $matches = Match::all();
//
//        foreach ($matches as $match){
//            $homeTeamName = $match->homeTeamName();
//            $awayTeamName = $match->awayTeamName();
//            $date = substr($match->date, 0, 10);
//            $searchString = $homeTeamName.'-'.$awayTeamName.'-'.$date;
//            var_dump($searchString);
//        }
//        die();
//        $crawler = Goutte::request('GET', 'http://www.footlive.com/score/torino-vs-as-roma-2016-09-25');
//
//        $crawler->filter('a > span')->each(function ($node) {
//            $text = ($node->text());
//            $link = ($node->attr('href'));
//            var_dump($text);
//        });
//


    }

    function array_search_partial($arr, $keyword)
    {
        $indexes = [];
        foreach ($arr as $index => $string) {
            if (strpos($string, '(W)') !== false || strpos($string, 'U1') !== false || strpos($string, 'U2') !== false || (strpos($string, ' B') !== false && strpos($string, ' B') == (strlen($string) - 2))|| (strpos($string, ' C') !== false && strpos($string, ' C') == (strlen($string) - 2))){
                continue;
            }
            if (strpos($string, $keyword) !== false)
                $indexes[] = $index;
        }
        return $indexes;
    }
}
