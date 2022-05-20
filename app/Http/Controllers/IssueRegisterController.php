<?php

namespace App\Http\Controllers;

use App\IssueRegister;
use Illuminate\Http\Request;

class IssueRegisterController extends Controller
{
    public function getIssueRegister($id) {
        $issueRegister = IssueRegister::getIssueRegister($id);

        return $issueRegister;
    }

    public function newIssueRegister(Request $request) {
        IssueRegister::newIssueRegister($request->issue_id, $request->priority, $request->quantitativeImpact, $request->qualitativeImpact);
    }

    public function editIssueRegister(Request $request, $id) {
        IssueRegister::editIssueRegister($request->priority, $request->quantitativeImpact, $request->qualitativeImpact, $id);
    }
}
