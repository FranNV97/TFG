<?php

namespace Tests\Feature;

use App\Issue;
use App\IssueRegister;
use App\Process;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IssueTest extends TestCase
{
    public function testCrearIssue()
    {

        $user = new User();
        $user->email = 'pruebas@gmail.com';
        $user->name = 'Pruebas';
        $user->password = bcrypt('contraseña');
        $user->save();

        $process = Process::newProcess('Prueba', null, null);
        $issue = Issue::newIssue('IssueDePrueba', null, 1, 1, $process, null, null, $user->id, $user->id);
        IssueRegister::newIssueRegister($issue, 3, null, null);

        $newIssue = Issue::findOrFail($issue);
        $newRegister = IssueRegister::getIssueRegister($issue);

        $this->assertEquals('IssueDePrueba', $newIssue->name);
        $this->assertEquals($process, $newIssue->process_id);
        $this->assertEquals($issue, $newRegister->issue_id);
        $this->assertEquals(3, $newRegister->priority);
    }

    public function testEditarIssue()
    {
        $user = User::where('email', 'pruebas@gmail.com')->first();
        $process = Process::where('name', '=', 'Prueba')->first();
        $issue = Issue::where('name', '=', 'IssueDePrueba')->first();

        Issue::editIssue('EditadaIssue', null, 2, 3, null, $user->id, $issue->id);
        IssueRegister::editIssueRegister(8, null, null, $issue->id);

        $newIssue = Issue::findOrFail($issue->id);
        $newRegister = IssueRegister::getIssueRegister($issue->id);

        $this->assertEquals('EditadaIssue', $newIssue->name);
        $this->assertEquals($process->id, $newIssue->process_id);
        $this->assertEquals(8, $newRegister->priority);
        $this->assertEquals($newIssue->id, $newRegister->issue_id);
    }

    public function testBorrarIssue()
    {
        $oldProcess = Process::where('name', '=', 'Prueba')->first();
        $oldIssue = Issue::where('name', '=', 'EditadaIssue')->first();

        Issue::deleteIssue($oldIssue->id);
        Process::deleteProcess($oldProcess->id);
        User::where('email', 'pruebas@gmail.com')->delete();

        $process = Process::where('name', '=', 'Prueba')->first();
        $issue = Issue::where('name', '=', 'EditadaIssue')->first();
        $register = IssueRegister::getIssueRegister($oldIssue->id);
        $user = User::where('email', 'pruebas@gmail.com')->first();

        $this->assertNull($process);
        $this->assertNull($issue);
        $this->assertNull($register);
        $this->assertNull($user);
    }
}
