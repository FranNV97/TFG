<?php

namespace App\Http\Controllers;

use App\IssueTask;
use Illuminate\Http\Request;

class IssueTaskController extends Controller
{
    public function newIssueTask(Request $request) {
        IssueTask::newIssueTask($request->issueId, $request->taskId);
    }

    public function getIssueTasks($id) {
        IssueTask::getIssueTasks($id);
    }

    public function deleteIssueTasks($id) {
        IssueTask::deleteIssueTasks($id);
    }
}
