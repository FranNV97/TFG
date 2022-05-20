<?php

namespace App\Http\Controllers;

use App\FiveWhy;
use Illuminate\Http\Request;

class FiveWhyController extends Controller
{
    public function getFiveWhys($id) {
        $fiveWhys = FiveWhy::getFiveWhys($id);

        return $fiveWhys;
    }

    public function newFiveWhys(Request $request, $id) {
        FiveWhy::newFiveWhys($request->issue_id);
    }
}
