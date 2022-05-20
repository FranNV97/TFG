<?php

namespace Tests\Feature;

use App\Notification;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function testCrearNotificacion()
    {
        $user = new User();
        $user->email = 'pruebas@gmail.com';
        $user->name = 'Pruebas';
        $user->password = bcrypt('contraseña');
        $user->save();

        $notification = Notification::newNotification('MensajeDePrueba', 1, 1, $user->id);

        $newNotification = Notification::findOrFail($notification);

        $this->assertEquals('MensajeDePrueba', $newNotification->message);
        $this->assertEquals($user->id, $newNotification->user_id);
    }

    public function testBorrarNotificacion()
    {
        $oldNotification = Notification::where('message', '=', 'MensajeDePrueba')->first();

        Notification::deleteNotification($oldNotification->id);
        User::where('email', '=', 'pruebas@gmail.com')->delete();

        $notificacion = Notification::where('message', '=', 'MensajeDePrueba')->first();
        $usuario = User::where('email', '=', 'pruebas@gmail.com')->first();

        $this->assertNull($notificacion);
        $this->assertNull($usuario);
    }
}
