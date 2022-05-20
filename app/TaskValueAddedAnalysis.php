<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskValueAddedAnalysis extends Model
{
    protected $table = 'tasks_value_added_analyses';

    public static function newTaskAnalysis($name, $type, $value_added_analysis_id, $task_id, $value, $reason) {
        $taskAnalysis = new TaskValueAddedAnalysis();

        if($name != null) {
            $taskAnalysis->name = $name;
        }

        if($type != null) {
            $taskAnalysis->type = $type;
        }

        if($value_added_analysis_id != null) {
            $taskAnalysis->value_added_analysis_id = $value_added_analysis_id;
        }

        if($task_id != null) {
            $taskAnalysis->task_id = $task_id;
        }

        if($value != null) {
            $taskAnalysis->value = $value;
        }

        if($reason != null) {
            $taskAnalysis->reason = $reason;
        }

        $taskAnalysis->save();

        return $taskAnalysis->id;
    }

    public static function editTaskAnalysis($value, $reason, $id) {
        $taskAnalysis = TaskValueAddedAnalysis::findOrFail($id);

        if($value != null) {
            $taskAnalysis->value = $value;
        }

        if($reason != null) {
            $taskAnalysis->reason = $reason;
        }

        $taskAnalysis->save();
    }

    public static function getTasksAnalysis($id) {
        $tasksAnalysis = TaskValueAddedAnalysis::where('value_added_analysis_id', $id)->get();

        return $tasksAnalysis;
    }
}
