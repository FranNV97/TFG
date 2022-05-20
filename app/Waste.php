<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    public static function getWastes($id) {
        $wastes = Waste::where('task_analysis_id', $id)->get();

        return $wastes;
    }

    public static function newWaste($name, $type, $task_analysis_id) {
        $waste = new Waste();

        if($name != null) {
            $waste->name = $name;
        }

        if($type != null) {
            $waste->type = $type;
        }

        if($task_analysis_id != null) {
            $waste->task_analysis_id = $task_analysis_id;
        }

        $waste->save();

        return $waste->id;
    }

    public static function deleteWaste($id) {
        $waste = Waste::findOrFail($id);

        $waste->delete();
    }
}
