<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->post('/user', function (Request $request) {
    return $request->user();
});

Route::post('/upload-csv', 'UploadController@uploadCsv');
Route::get('/upload/sync-external-stats', 'UploadController@syncExternalStats');

Route::get('/stats/bet-types', 'StatsController@getByBetType');
Route::get('/stats/markets', 'StatsController@getByMarket');


