<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExceptionLog extends Model
{
    protected $fillable = [
        'smarket_row_id', 'exception'];

    public static function createLog($smarketRowId, $exception)
    {
        self::create(array(
            'smarket_row_id' => $smarketRowId,
            'exception' => $exception));
    }
}
