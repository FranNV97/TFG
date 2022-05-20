<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueRegister extends Model
{
    protected $table = 'issue_registers';

    public static function getIssueRegister($id) {
        $issueRegister = IssueRegister::where('issue_id', $id)->first();

        return $issueRegister;
    }

    public static function newIssueRegister($issueId, $priority, $quantitativeImpact, $qualitativeImpact) {
        $issueRegister = new IssueRegister();

        if($issueId != null) {
            $issueRegister->issue_id = $issueId;
        }

        if($priority != null) {
            $issueRegister->priority = $priority;
        }

        if($quantitativeImpact != null) {
            $issueRegister->quantitativeImpact = $quantitativeImpact;
        }

        if($qualitativeImpact != null) {
            $issueRegister->qualitativeImpact = $qualitativeImpact;
        }

        $issueRegister->save();
    }

    public static function editIssueRegister($priority, $quantitativeImpact, $qualitativeImpact, $id) {
        $array = array();

        if($priority != null) {
            $array['priority'] = $priority;
        }

        if($quantitativeImpact != null) {
            $array['quantitativeImpact'] = $quantitativeImpact;
        }

        if($qualitativeImpact != null) {
            $array['qualitativeImpact'] = $qualitativeImpact;
        }

        IssueRegister::where('issue_id', $id)->update($array);

    }
}
