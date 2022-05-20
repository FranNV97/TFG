<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getProcessTasks($id) {
        $tasks = Task::getProcessTasks($id);

        return $tasks;
    }

    public function newTask(Request $request)
    {
        $id = Task::newTask($request->name, $request->type, $request->processId);

        return $id;
    }

    public function editTask(Request $request, $id)
    {
        Task::editTask($request->name, $request->type, $request->order, $id);
    }

    public function deleteTask($id)
    {
        Task::deleteTask($id);
    }
}
