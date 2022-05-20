<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FiveWhy extends Model
{
    protected $table = 'five_whies';

    public static function getFiveWhys($id) {
        $fiveWhys = FiveWhy::where('issue_id', $id)->first();

        return $fiveWhys;
    }

    public static function newFiveWhys($issueId) {
        $fiveWhys = new FiveWhy();

        $fiveWhys->issue_id = $issueId;

        $fiveWhys->save();
    }

}
