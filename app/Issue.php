<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $table = 'issues';

    public static function getIssues($type) {
        $issues = Issue::where('type', $type)->get();

        return $issues;
    }

    public static function newIssue($name, $description, $type, $state, $processId, $issueId, $recorte, $creado, $modificado) {
        $issue = new Issue();

        if($name != null) {
            $issue->name = $name;
        }

        if($description != null) {
            $issue->description = $description;
        }

        if($type != null) {
            $issue->type = $type;
        }

        if($state != null) {
            $issue->state = $state;
        }

        if($processId != null) {
            $issue->process_id = $processId;
        }

        if($issueId != null) {
            $issue->issue_id = $issueId;
        }

        if($recorte != null) {
            $issue->recorte = $recorte;
        }

        if($recorte != null) {
            $issue->recorte = $recorte;
        }

        $issue->creado = $creado;

        $issue->modificado = $modificado;

        $issue->save();

        return $issue->id;
    }

    public static function editIssue($name, $description, $type, $state, $recorte, $modificado, $id) {
        $issue = Issue::findOrFail($id);

        if($name != null) {
            $issue->name = $name;
        }

        if($description != null) {
            $issue->description = $description;
        }

        if($type != null) {
            $issue->type = $type;
        }

        if($state != null) {
            $issue->state = $state;
        }

        if($recorte != null) {
            $issue->recorte = $recorte;
        }

        $issue->modificado = $modificado;

        $issue->save();
    }

    public static function deleteIssue($id) {
        $issue = Issue::findOrFail($id);

        IssueTask::deleteIssueTasks($id);
        IssueRegister::where('issue_id', $id)->delete();

        $issue->delete();
    }

    public static function getIssue($id) {
        $issue = Issue::findOrFail($id);

        return $issue;
    }

    public static function getSubproblems($id) {
        $issues = Issue::where('issue_id', $id)->get();

        return $issues;
    }

    public static function getRootProblems() {
        $issues = Issue::where('type', 2)->where('issue_id', null)->get();

        return $issues;
    }
}
