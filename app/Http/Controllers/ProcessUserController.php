<?php

namespace App\Http\Controllers;

use App\ProcessUser;
use Illuminate\Http\Request;

class ProcessUserController extends Controller
{
    public function getProcessUsers($id) {
        $participantes = ProcessUser::getProcessUsers($id);

        return $participantes;
    }

    public function getUsersRole($role) {
        return ProcessUser::getUsersRole($role);
    }

    public function getProcessUsersRole($id, $role) {
        return ProcessUser::getProcessUsersRole($id, $role);
    }

    public function newProcessUser(Request $request)
    {
        ProcessUser::newProcessUser($request->processId, $request->userId, $request->role);
    }

    public function editProcessUser(Request $request, $idUsuario, $idProceso)
    {
        ProcessUser::editProcessUser($request->role, $idUsuario, $idProceso);
    }

    public function deleteProcessUser($idUsuario, $idProceso)
    {
        ProcessUser::deleteProcessUser($idUsuario, $idProceso);
    }
}
