<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcessUser extends Model
{
    protected $table = 'processes_users';

    public static function getProcessUsers($id) {
        $participantes = DB::select('select u.*, pu.role from users u left join processes_users pu on u.id = pu.user_id where pu.process_id = ?', [$id]);

        return $participantes;
    }

    public static function getUsersRole($role) {
        $users = DB::select('select u.*, pu.role from users u left join processes_users pu on u.id = pu.user_id where pu.role = ?', [$role]);

        return $users;
    }

    public static function getProcessUsersRole($id, $role) {
        $users = DB::select('select u.*, pu.role from users u left join processes_users pu on u.id = pu.user_id where pu.process_id = ? and pu.role = ?', [$id, $role]);

        return $users;
    }

    public static function newProcessUser($processId, $userId, $role) {
        $participante = new ProcessUser();

        if($userId != null) {
            $participante->user_id = $userId;
        }

        if($processId != null) {
            $participante->process_id = $processId;
        }

        if($role != null) {
            $participante->role = $role;
        }

        $participante->save();
    }

    public static function editProcessUser($role, $idUsuario, $idProceso) {
        $participante = ProcessUser::where('user_id', $idUsuario)->where('process_id', $idProceso)->first();

        if($role != null) {
            $participante->role = $role;
        }

        $participante->save();
    }

    public static function deleteProcessUser($idUsuario, $idProceso) {
        ProcessUser::where('user_id', $idUsuario)->where('process_id', $idProceso)->delete();
    }

    public static function getProcessesUser() {

        $idUser = Auth::user()->id;

        return json_encode(DB::select('select p.*, pu.role from processes p left join processes_users pu on p.id = pu.process_id where pu.user_id = ?', [$idUser]));
    }
}
