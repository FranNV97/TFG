<?php

namespace App\Http\Controllers;

use App\Documentation;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function getProcessDocumentations($id) {
        $documentations = Documentation::getProcessDocumentations($id);

        return $documentations;
    }

    public function newDocument(Request $request)
    {
        $id = Documentation::newDocument($request->name, $request->file, $request->type, $request->processId, $request->issueRegisterId);

        return $id;
    }

    public function deleteDocument($id) {
        Documentation::deleteDocument($id);
    }

    public function getIssueRegisterDocumentations($id) {
        $documentations = Documentation::getIssueRegisterDocumentations($id);

        return $documentations;
    }

    public function getProcessDocumentationCut($id) {
        return Documentation::getProcessDocumentationCut($id);
    }
}
