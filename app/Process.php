<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $table = 'processes';

    public static function getAllProcesses() {
        $processes = Process::all();

        return $processes;
    }

    public static function newProcess($name, $description, $type) {
        $process = new Process();

        if($name != null) {
            $process->name = $name;
        }

        if($description != null) {
            $process->description = $description;
        }

        if($type != null) {
            $process->type = $type;
        }

        $process->save();

        return $process->id;
    }

    public static function editProcess($name, $descripction, $type, $id) {
        $process = Process::findOrFail($id);

        if($name != null) {
            $process->name = $name;
        }

        if($descripction != null) {
            $process->description = $descripction;
        }

        if($type != null) {
            $process->type = $type;
        }

        $process->save();
    }

    public static function deleteProcess($id) {
        $tareas = Task::getProcessTasks($id);

        foreach ($tareas as $valor) {
            Task::deleteTask($valor->id);
        }

        $documentos = Documentation::getProcessDocumentations($id);

        foreach ($documentos as $valor) {
            Documentation::deleteDocument($valor->id);
        }

        $participantes = ProcessUser::where('process_id', $id)->get();

        foreach ($participantes as $valor) {
            ProcessUser::deleteProcessUser($valor->user_id, $valor->process_id);
        }

        $objetivos = Objective::getProcessObjectives($id);

        foreach ($objetivos as $valor) {
            Objective::deleteObjective($valor->id);
        }

        $proceso = Process::findOrFail($id);
        $proceso->delete();
    }

    public static function getProcess($id) {
        $process = Process::findOrFail($id);

        return $process;
    }
}
