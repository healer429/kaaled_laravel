<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EarningController extends Controller
{
    public function getEarnings() {
        return response()->json(array(
            'status' => 1,
            'data' => auth()->user()->earnings
        ));
    }
}
