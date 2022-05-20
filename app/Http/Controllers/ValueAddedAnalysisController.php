<?php

namespace App\Http\Controllers;

use App\ValueAddedAnalysis;
use Illuminate\Http\Request;

class ValueAddedAnalysisController extends Controller
{
    public function newAnalysis(Request $request)
    {

        return ValueAddedAnalysis::newAnalysis($request->process_id, $request->finalizado);
    }

    public function editAnalysis(Request $request, $id)
    {

        ValueAddedAnalysis::editAnalysis($request->finalizado, $id);
    }

    public function getAnalysisBean($id)
    {

        return ValueAddedAnalysis::getAnalysisBean($id);
    }
}
