<?php

namespace App\Http\Controllers;

use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function myProfile() {
        $user = User::getUser(Auth::user()->id);
        $notifications = Notification::getNotifications($user->id);

        return view('profile', ['user' => $user, 'badge' => count($notifications)]);
    }

    public function updateUser(Request $request, $id)
    {
        User::editUser($request->name, $request->surname, null, $request->address, $request->bdate, $request->email, $id);
    }

    public function getUsers() {
        $users = User::getUsers();

        return $users;
    }
}
