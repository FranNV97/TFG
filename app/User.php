<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getUser($id) {
        $user = User::findOrFail($id);

        return $user;
    }

    public static function editUser($name, $surname, $password, $address, $bdate, $email, $id) {
        $user = User::findOrFail($id);

        if($name != null) {
            $user->name = $name;
        }

        if($surname != null) {
            $user->surname = $surname;
        }

        if($password != null) {
            $user->password = bcrypt($password);
        }

        if($address != null) {
            $user->address = $address;
        }

        if($bdate != null) {
            $user->bdate = $bdate;
        }

        if($email != null) {
            $user->email = $email;
        }

        $user->save();
    }

    public static function getUsers() {
        $users = User::all();

        return $users;
    }
}
