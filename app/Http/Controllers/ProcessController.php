<?php

namespace App\Http\Controllers;

use App\Documentation;
use App\Notification;
use App\Objective;
use App\Process;
use App\ProcessUser;
use App\Task;
use App\User;
use App\ValueAddedAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessController extends Controller
{
    public function getProcessesView() {

        $user = User::getUser(Auth::user()->id);
        $notifications = Notification::getNotifications($user->id);
        $permisos = ProcessUser::getProcessesUser();

        return view('processes', ['user' => $user, 'permisos' => $permisos, 'badge' => count($notifications)]);
    }

    public function getAllProcesses() {
        $processes = Process::getAllProcesses();

        return $processes;
    }

    public function newProcess(Request $request)
    {
        $id = Process::newProcess($request->name, $request->description, null);

        return $id;
    }

    public function editProcess(Request $request, $id)
    {
        Process::editProcess($request->name, $request->description, null, $id);
    }

    public function deleteProcess($id) {
        Process::deleteProcess($id);
    }

    public function getProcess($id)
    {
        $process = Process::getProcess($id);

        return $process;
    }

    public function getProcessView($id)
    {
        $user = User::getUser(Auth::user()->id);
        $proceso = Process::getProcess($id);
        $tareas = Task::getProcessTasks($id);
        $documentos = Documentation::getProcessDocumentations($id);
        $objetivos = Objective::getProcessObjectives($id);
        $notifications = Notification::getNotifications($user->id);
        $permisos = ProcessUser::getProcessesUser();

        return view('process', ['user' => $user, 'process' => $proceso, 'tasks' => $tareas, 'documents' => $documentos, 'objectives' => $objetivos, 'permisos' => $permisos, 'badge' => count($notifications)]);
    }

    public function getValueAddedView($id)
    {
        $user = User::getUser(Auth::user()->id);
        $proceso = Process::getProcess($id);
        $tareas = Task::getProcessTasks($id);
        $analysis = ValueAddedAnalysis::getAnalysis($id);
        $notifications = Notification::getNotifications($user->id);

        return view('valueadded', ['user' => $user, 'process' => $proceso, 'tasks' => $tareas, 'analysis' => $analysis, 'badge' => count($notifications)]);
    }
}
