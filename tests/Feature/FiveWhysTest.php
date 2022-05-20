<?php

namespace Tests\Feature;

use App\FiveWhy;
use App\Issue;
use App\Process;
use App\User;
use App\Why;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FiveWhysTest extends TestCase
{
    public function testCrearFiveWhys()
    {

        $user = new User();
        $user->email = 'pruebas@gmail.com';
        $user->name = 'Pruebas';
        $user->password = bcrypt('contraseña');
        $user->save();

        $process = Process::newProcess('Prueba', null, null);
        $issue = Issue::newIssue('IssueDePrueba', null, 1, 1, $process, null, null, $user->id, $user->id);

        FiveWhy::newFiveWhys($issue);
        $why = Why::newWhy('RespuestaDePrueba', 1, false, $issue, null);

        $newFiveWhys = FiveWhy::where('issue_id', $issue)->first();
        $newWhy = Why::findOrFail($why);

        $this->assertEquals($issue, $newFiveWhys->issue_id);
        $this->assertEquals('RespuestaDePrueba', $newWhy->answer);
        $this->assertEquals(1, $newWhy->level);
        $this->assertEquals($issue, $newWhy->five_why_id);
    }

    public function testBorrarFiveWhys()
    {
        $oldProcess = Process::where('name', '=', 'Prueba')->first();
        $oldIssue = Issue::where('name', '=', 'IssueDePrueba')->first();

        Why::where('answer', 'RespuestaDePrueba')->delete();
        FiveWhy::where('issue_id', $oldIssue->id)->delete();
        Issue::deleteIssue($oldIssue->id);
        Process::deleteProcess($oldProcess->id);
        User::where('email', 'pruebas@gmail.com')->delete();

        $process = Process::where('name', '=', 'Prueba')->first();
        $issue = Issue::where('name', '=', 'EditadaIssue')->first();
        $user = User::where('email', 'pruebas@gmail.com')->first();
        $fiveWhy = FiveWhy::where('issue_id', $oldIssue->id)->first();
        $why = Why::where('answer', 'RespuestaDePrueba')->first();

        $this->assertNull($process);
        $this->assertNull($issue);
        $this->assertNull($user);
        $this->assertNull($fiveWhy);
        $this->assertNull($why);
    }
}
