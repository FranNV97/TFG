<?php

namespace Tests\Feature;

use App\Objective;
use App\Process;
use App\ProcessUser;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessTest extends TestCase
{
    public function testCrearProceso()
    {

        $process = Process::newProcess('Prueba', null, null);
        $objetivo = Objective::newObjective('ObjetivoPrueba', 2, '200%', $process);
        $task = Task::newTask('TareaDePrueba', 2, $process);

        $newProcess = Process::findOrFail($process);
        $newObjective = Objective::findOrFail($objetivo);
        $newTask = Task::findOrFail($task);

        $this->assertEquals('Prueba', $newProcess->name);
        $this->assertEquals('ObjetivoPrueba', $newObjective->name);
        $this->assertEquals($newProcess->id, $newObjective->process_id);
        $this->assertEquals('TareaDePrueba', $newTask->name);
        $this->assertEquals($newProcess->id, $newTask->process_id);
    }

    public function testEditarProceso()
    {

        $process = Process::where('name', '=', 'Prueba')->first();
        $objetivo = Objective::where('name', '=', 'ObjetivoPrueba')->first();
        $tarea = Task::where('name', '=', 'TareaDePrueba')->first();

        Process::editProcess('NuevaPrueba', null, null, $process->id);
        Objective::editObjective('EditadoObjetivo', null, null, $objetivo->id);
        Task::editTask('EditadaTarea', null, null, $tarea->id);

        $newProcess = Process::findOrFail($process->id);
        $newObjective = Objective::findOrFail($objetivo->id);
        $newTask = Task::findOrFail($tarea->id);

        $this->assertEquals('NuevaPrueba', $newProcess->name);
        $this->assertEquals('EditadoObjetivo', $newObjective->name);
        $this->assertEquals($process->id, $newObjective->process_id);
        $this->assertEquals('EditadaTarea', $newTask->name);
        $this->assertEquals($process->id, $newTask->process_id);
    }

    public function testBorrarProceso()
    {
        $oldProcess = Process::where('name', '=', 'NuevaPrueba')->first();

        Process::deleteProcess($oldProcess->id);

        $process = Process::where('name', '=', 'NuevaPrueba')->first();
        $objetivo = Objective::where('name', '=', 'EditadoObjetivo')->first();
        $tarea = Task::where('name', '=', 'EditadaTarea')->first();

        $this->assertNull($process);
        $this->assertNull($objetivo);
        $this->assertNull($tarea);
    }
}
