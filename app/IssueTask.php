<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IssueTask extends Model
{
    protected $table ='issues_tasks';

    public static function newIssueTask($issueId, $taskId) {
        $issueTask = new IssueTask();

        if($issueId != null) {
            $issueTask->issue_id = $issueId;
        }

        if($taskId != null) {
            $issueTask->task_id = $taskId;
        }

        $issueTask->save();
    }

    public static function getIssueTasks($id) {
        $tareas = DB::select('select t.* from tasks t left join issues_tasks it on t.id = it.task_id where it.issue_id = ?', [$id]);

        echo json_encode( $tareas ) ;
    }

    public static function deleteIssueTasks($id) {
        IssueTask::where('issue_id', $id)->delete();
    }
}
