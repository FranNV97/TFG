<?php

namespace App\Http\Controllers;

use App\Waste;
use Illuminate\Http\Request;

class WasteController extends Controller
{
    public function getWastes($id)
    {
        return Waste::getWastes($id);
    }

    public function newWaste(Request $request)
    {
        return Waste::newWaste($request->name, $request->type, $request->task_analysis_id);
    }

    public function deleteWaste($id)
    {
        Waste::deleteWaste($id);
    }
}
