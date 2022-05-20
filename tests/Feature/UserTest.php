<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testCrearUsuario() {

        $user = new User();
        $user->email = 'pruebas@gmail.com';
        $user->name = 'Pruebas';
        $user->password = bcrypt('contraseña');
        $user->save();

        $actual = User::where('email', '=', 'pruebas@gmail.com')->first();

        $this->assertEquals('Pruebas', $actual->name);
    }

    public function testEditarUsuario() {

        $last = User::where('email', '=', 'pruebas@gmail.com')->first();

        User::editUser('Cambio', 'Nuevo', null, null, null, null, $last->id);

        $actual = User::where('email', '=', 'pruebas@gmail.com')->first();

        $this->assertEquals('Cambio', $actual->name);
        $this->assertEquals('Nuevo', $actual->surname);
    }

    public function testObtenerUsuario() {
        $last = User::where('email', '=', 'pruebas@gmail.com')->first();

        $usuario = User::getUser($last->id);

        $this->assertEquals('Cambio', $usuario->name);
    }

    public function testBorrarUsuario() {

        $borrar = User::where('email', '=', 'pruebas@gmail.com')->delete();

        $actual = User::where('email', '=', 'pruebas@gmail.com')->first();

        $this->assertNull($actual);
    }
}
