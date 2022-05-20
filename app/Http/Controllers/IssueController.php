<?php

namespace App\Http\Controllers;

use App\FiveWhy;
use App\Issue;
use App\Notification;
use App\ProcessUser;
use App\User;
use App\Why;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function getIssues() {

        $user = User::getUser(Auth::user()->id);
        $notifications = Notification::getNotifications($user->id);
        $permisos = ProcessUser::getProcessesUser();

        return view('issues', ['user' => $user, 'permisos' => $permisos, 'badge' => count($notifications)]);
    }

    public function getIssuesType($type) {
        $issues = Issue::getIssues($type);

        return $issues;
    }

    public function newIssue(Request $request) {
        $issue = Issue::newIssue($request->name, $request->description, $request->type, $request->state, $request->processId, $request->issueId, $request->recorte, $request->creado, $request->modificado);

        return $issue;
    }

    public function editIssue(Request $request, $id) {
        Issue::editIssue($request->name, $request->description, $request->type, $request->state, $request->recorte, $request->modificado, $id);
    }

    public function deleteIssue($id) {
        Issue::deleteIssue($id);
    }

    public function getFiveWhys($id) {

        $user = User::getUser(Auth::user()->id);
        $issue = Issue::getIssue($id);
        $whys = Why::getWhys($id);
        $notifications = Notification::getNotifications($user->id);

        return view('fivewhys', ['user' => $user, 'issue' => $issue, 'whys' => $whys, 'badge' => count($notifications)]);
    }

    public function getIssue($id) {
        return Issue::getIssue($id);
    }

    public function getSubproblems($id) {
        return Issue::getSubproblems($id);
    }

    public function getIncidences() {

        $user = User::getUser(Auth::user()->id);
        $notifications = Notification::getNotifications($user->id);
        $permisos = ProcessUser::getProcessesUser();

        return view('incidences', ['user' => $user, 'permisos' => $permisos, 'badge' => count($notifications)]);
    }

    public function getRootProblems() {
        return Issue::getRootProblems();
    }

    public function getIshikawa($id) {

        $user = User::getUser(Auth::user()->id);
        $issue = Issue::getIssue($id);
        $notifications = Notification::getNotifications($user->id);

        return view('ishikawa', ['user' => $user, 'issue' => $issue, 'badge' => count($notifications)]);
    }
}
