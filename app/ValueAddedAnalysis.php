<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValueAddedAnalysis extends Model
{
    protected $table = 'value_added_analyses';

    public static function getAnalysis($id)
    {
        $analysis = ValueAddedAnalysis::where('process_id', $id)->get();

        return $analysis;
    }

    public static function newAnalysis($process_id, $finalizado)
    {
        $analysis = new ValueAddedAnalysis();

        if ($process_id != null) {
            $analysis->process_id = $process_id;
        }

        $analysis->finalizado = $finalizado;

        $analysis->save();

        return $analysis;
    }

    public static function editAnalysis($finalizado, $id)
    {
        $analysis = ValueAddedAnalysis::findOrFail($id);

        $analysis->finalizado = $finalizado;

        $analysis->save();
    }

    public static function getAnalysisBean($id)
    {
        return ValueAddedAnalysis::findOrFail($id);
    }
}
