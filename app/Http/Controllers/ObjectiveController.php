<?php

namespace App\Http\Controllers;

use App\Objective;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    public function getProcessObjectives($id) {
        $objetivos = Objective::getProcessObjectives($id);

        return $objetivos;
    }

    public function newObjective(Request $request)
    {
        $id = Objective::newObjective($request->name, $request->type, $request->valor, $request->processId);

        return $id;
    }

    public function editObjective(Request $request, $id)
    {
        Objective::editObjective($request->name, $request->type, $request->valor, $id);
    }

    public function deleteObjective($id)
    {
        Objective::deleteObjective($id);
    }
}
