<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    protected $table = 'objectives';

    public static function getProcessObjectives($id) {
        $objetivos = Objective::where('process_id', $id)->get();

        return $objetivos;
    }

    public static function newObjective($name, $type, $valor, $processId) {
        $objetivo = new Objective();

        if($name != null) {
            $objetivo->name = $name;
        }

        if($type != null) {
            $objetivo->type = $type;
        }

        if($valor != null) {
            $objetivo->valor = $valor;
        }

        if($processId != null) {
            $objetivo->process_id = $processId;
        }

        $objetivo->save();

        return $objetivo->id;
    }

    public static function editObjective($name, $type, $valor, $id) {
        $objetivo = Objective::findOrFail($id);

        if($name != null) {
            $objetivo->name = $name;
        }

        if($type != null) {
            $objetivo->type = $type;
        }

        if($valor != null) {
            $objetivo->valor = $valor;
        }

        $objetivo->save();
    }

    public static function deleteObjective($id) {
        $objetivo = Objective::findOrFail($id);

        $objetivo->delete();
    }

}
