<?php

namespace App\Http\Controllers;

use App\Why;
use Illuminate\Http\Request;

class WhyController extends Controller
{
    public function newWhy(Request $request) {
        $id = Why::newWhy($request->answer, $request->level, $request->rootCause, $request->five_why_id, $request->why_id);

        return $id;
    }

    public function changeRootCause(Request $request, $id) {
        Why::changeRootCause($request->rootCause, $id);
    }
}
