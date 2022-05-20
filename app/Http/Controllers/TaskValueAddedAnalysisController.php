<?php

namespace App\Http\Controllers;

use App\TaskValueAddedAnalysis;
use Illuminate\Http\Request;

class TaskValueAddedAnalysisController extends Controller
{
    public function newTaskAnalysis(Request $request)
    {

        return TaskValueAddedAnalysis::newTaskAnalysis($request->name, $request->type, $request->value_added_analysis_id, $request->task_id, $request->value, $request->reason);
    }

    public function editTaskAnalysis(Request $request, $id)
    {

        TaskValueAddedAnalysis::editTaskAnalysis($request->value, $request->reason, $id);
    }

    public function getTasksAnalysis($id)
    {

        return TaskValueAddedAnalysis::getTasksAnalysis($id);
    }
}
