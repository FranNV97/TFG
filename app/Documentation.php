<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    protected $table = 'documentations';

    public static function getProcessDocumentations($id) {
        $documents = Documentation::where('process_id', $id)->get();

        return $documents;
    }

    public static function newDocument($name, $file, $type, $processId, $issueRegisterId) {
        $document = new Documentation();

        if($name != null) {
            $document->name = $name;
        }

        if($file != null) {
            $document->file = $file;
        }

        if($type != null) {
            $document->type = $type;
        }

        if($processId != null) {
            $document->process_id = $processId;
        }

        if($issueRegisterId != null) {
            $document->issue_register_id = $issueRegisterId;
        }

        $document->save();

        return $document->id;
    }

    public static function deleteDocument($id) {
        $document = Documentation::findOrFail($id);

        $document->delete();
    }

    public static function getIssueRegisterDocumentations($id) {
        $documents = Documentation::where('issue_register_id', $id)->get();

        return $documents;
    }

    public static function getProcessDocumentationCut($id) {
        $document = Documentation::where('process_id', $id)->where('type', 2)->first();

        return $document;
    }
}
