<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    public static function getProcessTasks($id) {
        $tasks = Task::where('process_id', $id)->orderBy('order', 'ASC')->get();

        return $tasks;
    }

    public static function newTask($name, $type, $processId) {
        $task = new Task();

        if($name != null) {
            $task->name = $name;
        }

        if($type != null) {
            $task->type = $type;
        }

        if($processId != null) {
            $task->process_id = $processId;
        }

        $task->save();

        return $task->id;
    }

    public static function editTask($name, $type, $order, $id) {
        $task = Task::findOrFail($id);

        if($name != null) {
            $task->name = $name;
        }

        if($type != null) {
            $task->type = $type;
        }

        if($order != null) {
            $task->order = $order;
        }

        $task->save();
    }

    public static function deleteTask($id) {
        $task = Task::findOrFail($id);

        $task->delete();
    }
}
