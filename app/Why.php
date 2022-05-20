<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Why extends Model
{
    protected $table = 'whies';

    public static function getWhys($id) {
        $whys = DB::select('select w.* from whies w left join five_whies fw on w.five_why_id = fw.issue_id where w.five_why_id = ?', [$id]);

        return $whys;
    }

    public static function newWhy($answer, $level, $rootCause, $five_why_id, $why_id) {
        $why = new Why();

        if($answer != null) {
            $why->answer = $answer;
        }

        if($level != null) {
            $why->level = $level;
        }

        if($rootCause != null) {
            $why->rootCause = $rootCause;
        }

        if($five_why_id != null) {
            $why->five_why_id = $five_why_id;
        }

        if($why_id != null) {
            $why->why_id = $why_id;
        }

        $why->save();

        return $why->id;
    }

    public static function changeRootCause($rootCause, $id) {
        $why = Why::findOrFail($id);

        if($rootCause != null) {
            $why->rootCause = $rootCause;
        }

        $why->save();
    }
}
